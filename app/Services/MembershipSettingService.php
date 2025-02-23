<?php

namespace App\Services;

use App\Repositories\MembershipSettingRepository;

/**
 * MembershipSettingService
 * Encapsulates business logic for membership settings.
 */
class MembershipSettingService
{
    protected MembershipSettingRepository $membershipRepo;

    /**
     * Inject the MembershipSettingRepository.
     */
    public function __construct(MembershipSettingRepository $membershipRepo)
    {
        $this->membershipRepo = $membershipRepo;
    }

    /**
     * Retrieve all membership settings with optional pagination.
     */
    public function getAllMembershipSettings($perPage = null)
    {
        return $this->membershipRepo->all($perPage);
    }

    /**
     * Create a new membership setting.
     */
    public function createMembershipSetting(array $data)
    {
        return $this->membershipRepo->create($data);
    }

    /**
     * Delete a membership setting by ID.
     */
    public function deleteMembershipSetting($id)
    {
        return $this->membershipRepo->delete($id);
    }

    /**
     * Fetch the latest (or current) membership settings for usage.
     */
    public function getCurrentSettings()
    {
        return $this->membershipRepo->currentSettings();
    }
}
