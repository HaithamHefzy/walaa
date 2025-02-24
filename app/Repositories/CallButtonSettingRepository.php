<?php

namespace App\Repositories;

use App\Models\CallButtonSetting;

/**
 * CallButtonSettingRepository
 * Handles direct database operations for CallButtonSetting model,
 * focusing on retrieving and updating multiple buttons at once.
 */
class CallButtonSettingRepository
{
    /**
     * Retrieve all call button settings with optional pagination.
     *
     * @param int|null $perPage
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function all($perPage = null)
    {
        $query = CallButtonSetting::latest();
        return is_null($perPage) ? $query->get() : $query->paginate($perPage);
    }

    /**
     * Find a suitable button for a given number of people.
     * This is optional logic if needed.
     *
     * @param int $peopleCount
     * @return CallButtonSetting|null
     */
    public function findSuitableButton($peopleCount)
    {
        return CallButtonSetting::where('max_people', '>=', $peopleCount)
            ->orderBy('max_people', 'asc')
            ->first();
    }

    /**
     * Find a call button setting by button_type (e.g. 'A', 'B', 'C').
     *
     * @param string $buttonType
     * @return CallButtonSetting|null
     */
    public function findByButtonType(string $buttonType)
    {
        return CallButtonSetting::where('button_type', $buttonType)->first();
    }

    /**
     * Update the max_people for a specific call button by its type.
     *
     * @param string $buttonType
     * @param int $maxPeople
     * @return void
     */
    public function updateButton(string $buttonType, int $maxPeople): void
    {
        $button = $this->findByButtonType($buttonType);
        if ($button) {
            $button->max_people = $maxPeople;
            $button->save();
        }
    }
}
