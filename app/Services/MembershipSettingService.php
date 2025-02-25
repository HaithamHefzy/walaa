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
     *
     * @param MembershipSettingRepository $membershipRepo
     */
    public function __construct(MembershipSettingRepository $membershipRepo)
    {
        $this->membershipRepo = $membershipRepo;
    }

    /**
     * Retrieve all membership settings with optional pagination.
     *
     * @param int|null $perPage
     * @return mixed
     */
    public function getAllMembershipSettings($perPage = null)
    {
        return $this->membershipRepo->all($perPage);
    }

    /**
     * Retrieve the current (latest) membership settings.
     *
     * @return mixed
     */
    public function getCurrentSettings()
    {
        return $this->membershipRepo->currentSettings();
    }

    /**
     * Update membership settings in one request.
     * Expected input: ['platinum_visits' => X, 'gold_visits' => Y, 'silver_visits' => Z]
     *
     * @param array $data
     * @return void
     */
    public function updateSettings(array $data): void
    {
        $this->membershipRepo->updateSettings($data);
    }
}
