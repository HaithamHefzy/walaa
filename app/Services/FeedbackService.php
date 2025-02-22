<?php

namespace App\Services;

use App\Repositories\FeedbackRepository;
use App\Models\Feedback;
use App\Models\FeedbackManager;

class FeedbackService
{
    protected $feedbackRepo;

    /**
     * Inject FeedbackRepository into the service.
     */
    public function __construct(FeedbackRepository $feedbackRepo)
    {
        $this->feedbackRepo = $feedbackRepo;
    }

    /**
     * Retrieve all feedbacks with optional pagination.
     */
    public function getAllFeedbacks($perPage)
    {
        return $this->feedbackRepo->all($perPage);
    }

    /**
     * Create a new feedback.
     */
    public function createFeedback(array $data)
    {
        return $this->feedbackRepo->create($data);
    }

    /**
     * Delete a feedback by ID.
     */
    public function deleteFeedback($id)
    {
        return $this->feedbackRepo->delete($id);
    }

    /**
     * Assign a feedback to a manager.
     */
    public function assignFeedbackToManager($feedbackId, $managerId)
    {
        $feedback = Feedback::find($feedbackId);

        if ($feedback && $managerId) {
            $feedback->feedbackManagers()->attach($managerId);
            return true;
        }
        return false;
    }
}
