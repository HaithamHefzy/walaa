<?php

namespace App\Repositories;

use App\Models\MembershipSetting;

/**
 * MembershipSettingRepository
 * Handles direct database operations for the MembershipSetting model.
 */
class MembershipSettingRepository
{
    /**
     * Retrieve all membership settings with optional pagination.
     *
     * @param int|null $perPage
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function all($perPage = null)
    {
        $query = MembershipSetting::latest();
        return is_null($perPage) ? $query->get() : $query->paginate($perPage);
    }

    /**
     * Fetch the latest (or current) membership settings record.
     *
     * @return MembershipSetting|null
     */
    public function currentSettings()
    {
        return MembershipSetting::latest()->first();
    }

    /**
     * Update the membership settings.
     * Assumes there is only one record (ID = 1) holding the settings.
     *
     * @param array $data
     * @return void
     */
    public function updateSettings(array $data): void
    {
        $setting = MembershipSetting::find(1);
        if ($setting) {
            $setting->platinum_visits = $data['platinum_visits'];
            $setting->gold_visits     = $data['gold_visits'];
            $setting->silver_visits   = $data['silver_visits'];
            $setting->save();
        }
    }
}
