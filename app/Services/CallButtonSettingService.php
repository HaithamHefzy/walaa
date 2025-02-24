<?php

namespace App\Services;

use App\Repositories\CallButtonSettingRepository;

/**
 * CallButtonSettingService
 * Encapsulates business logic for call button settings,
 * focusing on retrieving and updating multiple buttons at once.
 */
class CallButtonSettingService
{
    protected CallButtonSettingRepository $buttonRepo;

    /**
     * Inject the CallButtonSettingRepository.
     *
     * @param CallButtonSettingRepository $buttonRepo
     */
    public function __construct(CallButtonSettingRepository $buttonRepo)
    {
        $this->buttonRepo = $buttonRepo;
    }

    /**
     * Retrieve all call button settings with optional pagination.
     *
     * @param int|null $perPage
     * @return mixed
     */
    public function getAllCallButtons($perPage = null)
    {
        return $this->buttonRepo->all($perPage);
    }

    /**
     * Find a suitable button for a given number of people (optional logic).
     *
     * @param int $peopleCount
     * @return \App\Models\CallButtonSetting|null
     */
    public function findSuitableButton($peopleCount)
    {
        return $this->buttonRepo->findSuitableButton($peopleCount);
    }

    /**
     * Update multiple call button settings at once.
     * Example input: ['A' => 3, 'B' => 5, 'C' => 7]
     *
     * @param array $buttonsData
     * @return void
     */
    public function updateMultipleButtons(array $buttonsData): void
    {
        foreach ($buttonsData as $buttonType => $maxPeople) {
            $this->buttonRepo->updateButton($buttonType, (int)$maxPeople);
        }
    }
}
