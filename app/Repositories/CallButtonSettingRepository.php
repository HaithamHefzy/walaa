<?php

namespace App\Repositories;

use App\Models\CallButtonSetting;

/**
 * CallButtonSettingRepository
 * Handles direct database operations for CallButtonSetting model.
 */
class CallButtonSettingRepository
{
    /**
     * Retrieve all call button settings with optional pagination.
     */
    public function all($perPage)
    {
        $query = CallButtonSetting::latest();
        return is_null($perPage) ? $query->get() : $query->paginate($perPage);
    }

    /**
     * Create a new call button setting record.
     */
    public function create(array $data)
    {
        return CallButtonSetting::create($data);
    }

    /**
     * Delete a call button setting by ID.
     */
    public function delete($id)
    {
        $button = CallButtonSetting::find($id);
        return $button ? $button->delete() : false;
    }

    /**
     * Find a call button setting by ID.
     */
    public function find($id)
    {
        return CallButtonSetting::find($id);
    }

    /**
     *  find a suitable button for a given number of people.
     * This is optional logic if needed.
     */
    public function findSuitableButton($peopleCount)
    {
        return CallButtonSetting::where('max_people', '>=', $peopleCount)
            ->orderBy('max_people', 'asc')
            ->first();
    }
}
