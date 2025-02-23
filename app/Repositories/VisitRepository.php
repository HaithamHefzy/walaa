<?php

namespace App\Repositories;

use App\Models\Visit;

/**
 * Handles direct database operations for the Visit model.
 */
class VisitRepository
{
    /**
     * Retrieve visits with optional pagination.
     */
    public function all($perPage)
    {
        $query = Visit::latest();
        return is_null($perPage) ? $query->get() : $query->paginate($perPage);
    }

    /**
     * Create a new visit record.
     */
    public function create(array $data)
    {
        return Visit::create($data);
    }

    /**
     * Delete a visit by ID.
     */
    public function delete($id)
    {
        $visit = Visit::find($id);
        return $visit ? $visit->delete() : false;
    }

    /**
     * Find a visit by ID if needed.
     */
    public function find($id)
    {
        return Visit::find($id);
    }
}
