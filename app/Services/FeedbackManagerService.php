<?php

namespace App\Services;

use App\Repositories\FeedbackManagerRepository;

class FeedbackManagerService
{
    protected $managerRepo;

    public function __construct(FeedbackManagerRepository $managerRepo)
    {
        $this->managerRepo = $managerRepo;
    }

    /**
     * Retrieve all feedback managers
     */
    public function getAllManagers($perPage)
    {
        return $this->managerRepo->all($perPage);
    }

    /**
     * Create a new feedback manager.
     */
    public function createManager($data)
    {
        return $this->managerRepo->create($data);
    }

    /**
     * Delete feedback manager by ID.
     */
    public function deleteManager($id)
    {
        return $this->managerRepo->delete($id);
    }
}
