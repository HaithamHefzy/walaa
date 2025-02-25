<?php

namespace App\Services;

use App\Repositories\VisitRepository;
use App\Models\Visit;
use App\Models\Table;
use App\Repositories\CallButtonSettingRepository;

/**
 * VisitService
 * Encapsulates business logic for visits (waiting or direct),
 * including table assignment (if provided), call by button, and special call.
 */
class VisitService
{
    protected $visitRepo;
    protected $callButtonRepo;

    /**
     * Inject the VisitRepository and the CallButtonSettingRepository.
     *
     * @param VisitRepository $visitRepo
     * @param CallButtonSettingRepository $callButtonRepo
     */
    public function __construct(
        VisitRepository $visitRepo,
        CallButtonSettingRepository $callButtonRepo
    ) {
        $this->visitRepo = $visitRepo;
        $this->callButtonRepo = $callButtonRepo;
    }

    /**
     * Retrieve all visits with optional pagination.
     *
     * @param int|null $perPage
     * @return mixed
     */
    public function getAllVisits($perPage = null)
    {
        return $this->visitRepo->all($perPage);
    }

    /**
     * Create a new visit record.
     * If table_id is provided, verify the table's capacity and availability.
     *
     * @param array $data
     * @return \App\Models\Visit|\Illuminate\Http\JsonResponse
     */
    public function createVisit(array $data)
    {
        // If table_id is provided, check capacity and availability
        if (!empty($data['table_id'])) {
            $table = Table::find($data['table_id']);

            // If the table doesn't exist or is not available, remove table_id
            if (!$table || $table->status !== 'available') {
                unset($data['table_id']);
            } else {
                // Check table capacity
                if (!empty($data['number_of_people']) && $data['number_of_people'] > $table->table_capacity) {
                    unset($data['table_id']);
                } else {
                    // Mark the table as unavailable
                    $table->status = 'unavailable';
                    $table->save();
                }
            }
        }

        // 1) Create the visit record
        $visit = $this->visitRepo->create($data);

        // 2) Calculate the waiting_number for today
        $today = now()->format('Y-m-d');
        $maxToday = Visit::whereDate('created_at', $today)->max('waiting_number');
        $nextNumber = $maxToday ? $maxToday + 1 : 1;

        // 3) Save the waiting_number in the visit
        $visit->waiting_number = $nextNumber;
        $visit->save();

        return $visit;
    }

    /**
     * Delete a visit by ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteVisit($id)
    {
        return $this->visitRepo->delete($id);
    }

    /**
     * Assign a table to an existing visit.
     *
     * @param int $visitId
     * @param int $tableId
     * @return string
     */
    public function assignTable($visitId, $tableId)
    {
        $visit = $this->visitRepo->find($visitId);
        if (!$visit) {
            return 'visit_not_found';
        }

        $table = Table::find($tableId);
        if (!$table) {
            return 'table_not_found';
        }

        if ($table->status === 'unavailable') {
            return 'table_already_taken';
        }

        if ($visit->number_of_people && $visit->number_of_people > $table->table_capacity) {
            return 'over_capacity';
        }

        $visit->table_id = $tableId;
        $visit->status   = 'called';
        $visit->save();

        $table->status = 'unavailable';
        $table->save();

        return 'assigned';
    }

    /**
     * Call by button (A, B, C, etc.).
     * This method finds the earliest waiting visit whose number_of_people falls within
     * the dynamic range for the given button, and optionally assigns a table if table_id is provided.
     * If table_id is not provided, it will simply mark the visit as 'called'.
     *
     * @param string $buttonType
     * @param int|null $tableId Optional table ID for assignment
     * @return array
     */
    public function callByButton(string $buttonType, $tableId = null)
    {
        // 1) Retrieve the call button setting by button type (e.g., 'A', 'B', 'C')
        $button = $this->callButtonRepo->findByButtonType($buttonType);
        if (!$button) {
            return ['status' => 'error', 'message' => "Button $buttonType not found"];
        }

        // 2) Determine the dynamic range for number_of_people based on button type.
        // Hardcoded ranges:
        // For 'A': range: 1 to A's max_people
        // For 'B': range: (A's max_people + 1) to B's max_people
        // For 'C': range: (B's max_people + 1) to C's max_people
        switch ($buttonType) {
            case 'A':
                $minPeople = 1;
                $maxPeople = $button->max_people;
                break;
            case 'B':
                $buttonA = $this->callButtonRepo->findByButtonType('A');
                $minPeople = $buttonA ? $buttonA->max_people + 1 : 1;
                $maxPeople = $button->max_people;
                break;
            case 'C':
                $buttonB = $this->callButtonRepo->findByButtonType('B');
                $minPeople = $buttonB ? $buttonB->max_people + 1 : 1;
                $maxPeople = $button->max_people;
                break;
            default:
                return ['status' => 'error', 'message' => "Unknown button type $buttonType"];
        }

        // 3) Find the earliest waiting visit with number_of_people within the determined range
        $visit = Visit::where('status', 'waiting')
            ->whereBetween('number_of_people', [$minPeople, $maxPeople])
            ->orderBy('created_at', 'asc')
            ->first();

        if (!$visit) {
            return ['status' => 'success', 'message' => "No waiting visit found for button $buttonType"];
        }

        // 4) If table_id is provided, check table availability and capacity, and assign the table.
        if ($tableId) {
            $table = Table::find($tableId);
            if (!$table || $table->status !== 'available') {
                return ['status' => 'error', 'message' => "Table not available"];
            }
            if ($visit->number_of_people > $table->table_capacity) {
                return ['status' => 'error', 'message' => "Table capacity is insufficient"];
            }
            $visit->table_id = $tableId;
            $table->status = 'unavailable';
            $table->save();
        }

        // 5) Mark the visit as 'called'
        $visit->status = 'called';
        $visit->save();

        // 6) Build display label using buttonType and waiting_number, e.g., "A 1"
        $displayLabel = $buttonType . ' ' . $visit->waiting_number;

        return [
            'status'   => 'success',
            'visit_id' => $visit->id,
            'message'  => "Visit {$displayLabel} called" . ($tableId ? " with table assignment" : " with no table assignment")
        ];
    }

    /**
     * Special call: Skip the queue and call a specific visit by ID.
     * Table assignment is optional.
     *
     * @param int $visitId
     * @param int|null $tableId Optional table assignment
     * @return array
     */
    /**
     * Special call: Skip the queue and call a specific visit by ID.
     * Table assignment is optional.
     *
     * This method builds a display label similar to a regular call,
     * e.g., "Special 1 (3 people) - 15 minutes ago".
     *
     * @param int $visitId
     * @param int|null $tableId Optional table assignment
     * @return array
     */
    public function specialCall($visitId, $tableId = null)
    {
        $visit = Visit::find($visitId);
        if (!$visit || $visit->status !== 'waiting') {
            return ['status' => 'error', 'message' => 'Visit not found or not waiting'];
        }

        // If table_id is provided, check table availability and capacity
        if ($tableId) {
            $table = Table::find($tableId);
            if (!$table) {
                return ['status' => 'error', 'message' => 'Table not found'];
            }
            if ($table->status !== 'available') {
                return ['status' => 'error', 'message' => 'Table is not available'];
            }
            if ($visit->number_of_people > $table->table_capacity) {
                return ['status' => 'error', 'message' => 'Table capacity is insufficient'];
            }
            // Assign the table
            $visit->table_id = $tableId;
            $table->status = 'unavailable';
            $table->save();
        }

        // Mark the visit as 'called'
        $visit->status = 'called';
        $visit->save();

        // Build display label similar to a regular call
        // e.g., "Special 1 (3 people) - 15 minutes ago"
        $timeSince = $visit->created_at ? $visit->created_at->diffForHumans() : '';
        $displayLabel = "Special " . $visit->waiting_number . " (" . $visit->number_of_people . " people) - " . $timeSince;

        return [
            'status'   => 'success',
            'visit_id' => $visit->id,
            'message'  => "Visit {$displayLabel} called by special call" . ($tableId ? " with table assignment" : "")
        ];
    }

}
