<?php

namespace App\Services;

use App\Repositories\VisitRepository;

/**
 * Encapsulates business logic for visits (waiting or direct).
 */
class VisitService
{
    protected $visitRepo;

    public function __construct(VisitRepository $visitRepo)
    {
        $this->visitRepo = $visitRepo;
    }

    /**
     * Retrieve all visits with optional pagination.
     */
    public function getAllVisits($perPage = null)
    {
        return $this->visitRepo->all($perPage);
    }

    /**
     * Create a new visit record.
     */
    public function createVisit(array $data)
    {
        // If table_id is provided, verify capacity and availability
        if (!empty($data['table_id'])) {
            $table = $this->tableRepo->find($data['table_id']);

            // Check if the table exists and is available
            if ($table && $table->status === 'available') {
                // Ensure the table capacity is >= the number of people
                if (!empty($data['number_of_people']) && $data['number_of_people'] > $table->room_capacity) {
                    // Return an error if capacity is exceeded
                    return $this->errorResponse('Number of people exceeds table capacity', 400);
                }

                // Mark the table as unavailable
                $table->status = 'unavailable';
                $table->save();
            }
        }

        // Create the visit record in the visits table
        return $this->visitRepo->create($data);
    }

    /**
     * Delete a visit by ID.
     */
    public function deleteVisit($id)
    {
        return $this->visitRepo->delete($id);
    }

    /**
     * Assign a table to an existing visit .
     */
    public function assignTable($visitId, $tableId)
    {
        $visit = $this->visitRepo->find($visitId);
        if (!$visit) {
            return 'visit_not_found';
        }

        $table = $this->tableRepo->find($tableId);
        if (!$table) {
            return 'table_not_found';
        }

        if ($table->status === 'unavailable') {
            return 'table_already_taken';
        }

        // Check capacity
        if ($visit->number_of_people && $visit->number_of_people > $table->room_capacity) {
            return 'over_capacity';
        }

        // Assign table
        $visit->table_id = $tableId;
        $visit->status   = 'called'; // or whatever logic you want
        $visit->save();

        // Mark table as unavailable
        $table->status = 'unavailable';
        $table->save();

        return 'assigned';
    }
}
