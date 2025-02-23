<?php

namespace App\Services;

use App\Repositories\CallButtonSettingRepository;

/**
 * CallButtonSettingService
 * Encapsulates business logic for call button settings.
 */
class CallButtonSettingService
{
    protected CallButtonSettingRepository $buttonRepo;

    /**
     * Inject the CallButtonSettingRepository.
     */
    public function __construct(CallButtonSettingRepository $buttonRepo)
    {
        $this->buttonRepo = $buttonRepo;
    }

    /**
     * Retrieve all call button settings with optional pagination.
     */
    public function getAllCallButtons($perPage = null)
    {
        return $this->buttonRepo->all($perPage);
    }

    /**
     * Create a new call button setting record.
     */
    public function createCallButton(array $data)
    {
        return $this->buttonRepo->create($data);
    }

    /**
     * Delete a call button setting by ID.
     */
    public function deleteCallButton($id)
    {
        return $this->buttonRepo->delete($id);
    }

    /**
     * Optionally find a suitable button for a given number of people.
     */
    public function findSuitableButton($peopleCount)
    {
        return $this->buttonRepo->findSuitableButton($peopleCount);
    }
}
