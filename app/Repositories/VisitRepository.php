<?php

namespace App\Repositories;

use App\Models\Visit;

/**
 * VisitRepository
 * Handles direct database operations for the Visit model.
 */
class VisitRepository
{
    /**
     * Retrieve visits with optional pagination.
     *
     * @param int|null $perPage
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function all($perPage = null)
    {
        $query = Visit::latest();
        return is_null($perPage) ? $query->get() : $query->paginate($perPage);
    }

    /**
     * Create a new visit record in the visits table.
     *
     * @param array $data
     * @return \App\Models\Visit
     */
    public function create(array $data)
    {
        dd('ff');
        $today = now()->format('Y-m-d');
        $maxToday = \App\Models\Visit::whereDate('created_at', $today)->max('waiting_number');
        $nextNumber = $maxToday ? $maxToday + 1 : 1;
        dd($nextNumber);
        $visit = Visit::create($data);
        // 2) Calculate the waiting_number for today

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
    public function delete($id)
    {
        $visit = Visit::find($id);
        return $visit ? $visit->delete() : false;
    }

    /**
     * Find a visit by ID.
     *
     * @param int $id
     * @return \App\Models\Visit|null
     */
    public function find($id)
    {
        return Visit::find($id);
    }
}
