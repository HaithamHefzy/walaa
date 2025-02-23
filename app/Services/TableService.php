<?php

namespace App\Services;

use App\Repositories\TableRepository;

/**
 * TableService
 * Encapsulates business logic for the Table entity.
 */
class TableService
{
    protected TableRepository $tableRepo;

    /**
     * Inject the TableRepository into the service.
     */
    public function __construct(TableRepository $tableRepo)
    {
        $this->tableRepo = $tableRepo;
    }

    /**
     * Retrieve all tables with optional pagination.
     */
    public function getAllTables($perPage = null)
    {
        return $this->tableRepo->all($perPage);
    }

    /**
     * Create a new table record.
     */
    public function createTable(array $data)
    {
        return $this->tableRepo->create($data);
    }

    /**
     * Delete a table by ID.
     */
    public function deleteTable($id)
    {
        return $this->tableRepo->delete($id);
    }

    /**
     * Free the table by setting status to 'available'.
     */
    public function freeTable($tableId)
    {
        $table = $this->tableRepo->free($tableId);
        if ($table) {
            return 'table_freed';
        }
        return 'not_found';
    }
}
