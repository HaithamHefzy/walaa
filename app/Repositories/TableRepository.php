<?php

namespace App\Repositories;

use App\Models\Table;

/**
 * TableRepository
 * Handles direct database operations for the Table model.
 */
class TableRepository
{
    /**
     * Retrieve all tables with optional pagination.
     *
     * @param int|null $perPage
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function all($perPage = null)
    {
        $query = Table::latest();
        return is_null($perPage) ? $query->get() : $query->paginate($perPage);
    }

    /**
     * Create a new table record.
     *
     * @param array $data
     * @return Table
     */
    public function create(array $data)
    {
        return Table::create($data);
    }

    /**
     * Delete a table by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $table = Table::find($id);
        return $table ? $table->delete() : false;
    }

    /**
     * Free a table by setting its status to 'available'.
     *
     * @param int $tableId
     * @return Table|null
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
