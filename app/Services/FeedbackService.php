<?php

namespace App\Services;

use App\Repositories\FeedbackRepository;
use App\Models\Feedback;
use App\Models\FeedbackManager;

/**
 * FeedbackService
 * Handles business logic for feedback, including filters, creation, deletion, and assignment.
 */
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
     * Retrieve filtered feedbacks with optional pagination.
     *
     * @param string|null $startDate
     * @param string|null $endDate
     * @param string|null $rating
     * @param int|null    $perPage
     * @return mixed
     */
    public function getFilteredFeedbacks($startDate, $endDate, $rating, $perPage)
    {
        return $this->feedbackRepo->filter($startDate, $endDate, $rating, $perPage);
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
            // Many-to-many relationship: feedback_managers
            $feedback->feedbackManagers()->attach($managerId);
            return true;
        }
        return false;
    }
}
