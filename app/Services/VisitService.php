<?php

namespace App\Services;

use App\Repositories\VisitRepository;
use App\Models\Visit;
use App\Models\Table;
use App\Repositories\CallButtonSettingRepository;

/**
 * VisitService
 * Encapsulates business logic for visits (waiting or direct),
 * including table assignment, call by button, and special call.
 */
class VisitService
{
    protected $visitRepo;
    protected $callButtonRepo;

    /**
     * Inject the VisitRepository and optionally the CallButtonSettingRepository.
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

        // Check capacity
        if ($visit->number_of_people && $visit->number_of_people > $table->table_capacity) {
            return 'over_capacity';
        }

        // Assign table
        $visit->table_id = $tableId;
        // Optionally mark the visit as 'called' if that fits your logic
        $visit->status   = 'called';
        $visit->save();

        // Mark table as unavailable
        $table->status = 'unavailable';
        $table->save();

        return 'assigned';
    }

    /**
     * Call by button (A, B, C, etc.).
     * 1) table_id is required for a regular call.
     * 2) Find the earliest waiting visit with number_of_people <= max_people.
     * 3) Assign the table if it is available and capacity is sufficient.
     * 4) Mark the visit as 'called'.
     *
     * @param string $buttonType
     * @param int|null $tableId
     * @return array
     */
    public function callByButton(string $buttonType, $tableId = null)
    {
        // 1) Ensure table_id is provided for a regular call
        if (!$tableId) {
            return ['status' => 'error', 'message' => 'Table assignment is required for regular call'];
        }

        // 2) Find the call button setting by button type (e.g., 'A', 'B', 'C')
        $button = $this->callButtonRepo->findByButtonType($buttonType);
        if (!$button) {
            return ['status' => 'error', 'message' => "Button $buttonType not found"];
        }

        // 3) Calculate dynamic range values manually (hardcoded)
        // For button 'A': 1 to A's max_people
        // For button 'B': (A's max_people + 1) to B's max_people
        // For button 'C': (B's max_people + 1) to C's max_people
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

        // 4) Find the earliest waiting visit with number_of_people within the range
        $visit = \App\Models\Visit::where('status', 'waiting')
            ->whereBetween('number_of_people', [$minPeople, $maxPeople])
            ->orderBy('created_at', 'asc')
            ->first();

        if (!$visit) {
            return ['status' => 'error', 'message' => "No waiting visit found for button $buttonType"];
        }

        // 5) Check the table availability and capacity
        $table = \App\Models\Table::find($tableId);
        if (!$table || $table->status !== 'available') {
            return ['status' => 'error', 'message' => "Table not available"];
        }
        if ($visit->number_of_people > $table->table_capacity) {
            return ['status' => 'error', 'message' => "Table capacity is insufficient"];
        }

        // 6) Assign the table to the visit and mark the table as unavailable
        $visit->table_id = $tableId;
        $table->status = 'unavailable';
        $table->save();

        // 7) Mark the visit as 'called' and save the changes
        $visit->status = 'called';
        $visit->save();

        // 8) Build display label using buttonType and waiting_number, e.g. "A 1"
        $displayLabel = $buttonType . ' ' . $visit->waiting_number;

        return [
            'status'   => 'success',
            'visit_id' => $visit->id,
            'message'  => "Visit {$displayLabel} called with table assignment"
        ];
    }


    /**
     * Special call: skip the queue and call a specific visit by ID.
     * Table assignment is optional.
     *
     * @param int $visitId
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

        return [
            'status' => 'success',
            'visit_id' => $visit->id,
            'message' => "Visit #{$visit->id} called by special call"
        ];
    }


}
