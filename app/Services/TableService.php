<?php

namespace App\Services;

use App\Repositories\TableRepository;

/**
 * TableService
 * Encapsulates business logic for table operations,
 * including listing, creating multiple tables, deleting, and freeing a table.
 */
class TableService
{
    protected TableRepository $tableRepo;

    /**
     * Inject the TableRepository into the service.
     *
     * @param TableRepository $tableRepo
     */
    public function __construct(TableRepository $tableRepo)
    {
        $this->tableRepo = $tableRepo;
    }

    /**
     * Retrieve all tables with optional pagination.
     *
     * @param int|null $perPage
     * @return mixed
     */
    public function getAllTables($perPage = null)
    {
        return $this->tableRepo->all($perPage);
    }

    /**
     * Create multiple table records from an array of table data.
     *
     * @param array $tablesData
     * @return array
     */
    public function createMultiple(array $tablesData): array
    {
        $created = [];
        foreach ($tablesData as $data) {
            // Default status to 'available' if not provided
            if (!isset($data['status'])) {
                $data['status'] = 'available';
            }
            $created[] = $this->tableRepo->create($data);
        }
        return $created;
    }


    /**
     * Delete a table by ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteTable($id)
    {
        return $this->tableRepo->delete($id);
    }

    /**
     * Free a table by setting status to 'available'.
     *
     * @param int $tableId
     * @return string
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
