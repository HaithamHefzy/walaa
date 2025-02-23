<?php

namespace App\Repositories;

use App\Models\Table;

/**
 * TableRepository
 * Handles direct database operations for Table model.
 */
class TableRepository
{
    /**
     * Retrieve all tables with optional pagination.
     */
    public function all($perPage = null)
    {
        $query = Table::latest();
        return is_null($perPage) ? $query->get() : $query->paginate($perPage);
    }

    /**
     * Create a new table record.
     */
    public function create(array $data)
    {
        return Table::create($data);
    }

    /**
     * Delete a table by ID.
     */
    public function delete($id)
    {
        $table = Table::find($id);
        return $table ? $table->delete() : false;
    }

    /**
     * Find a table by ID.
     */
    public function find($id)
    {
        return Table::find($id);
    }

    /**
     * Free a table by setting its status to 'available'.
     */
    public function free($tableId)
    {
        $table = Table::find($tableId);
        if ($table) {
            $table->status = 'available';
            $table->save();
            return $table;
        }
        return null;
    }
}
