<?php

namespace App\Repositories;

use App\Models\MembershipSetting;

/**
 * MembershipSettingRepository
 * Handles direct database operations for MembershipSetting model.
 */
class MembershipSettingRepository
{
    /**
     * Retrieve all membership settings with optional pagination.
     */
    public function all($perPage)
    {
        $query = MembershipSetting::latest();
        return is_null($perPage) ? $query->get() : $query->paginate($perPage);
    }

    /**
     * Create a new membership setting record.
     */
    public function create(array $data)
    {
        return MembershipSetting::create($data);
    }

    /**
     * Delete a membership setting by ID.
     */
    public function delete($id)
    {
        $setting = MembershipSetting::find($id);
        return $setting ? $setting->delete() : false;
    }

    /**
     * Find a membership setting by ID.
     */
    public function find($id)
    {
        return MembershipSetting::find($id);
    }

    /**
     * fetch the latest (or current) membership setting record.
     */
    public function currentSettings()
    {
        return MembershipSetting::latest()->first();
    }
}
