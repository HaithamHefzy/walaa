<?php

namespace App\Http\Controllers;

use App\Services\FeedbackService;
use App\Http\Requests\StoreFeedbackRequest;
use App\Http\Resources\FeedbackResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * FeedbackController
 * Handles listing (with filters), creating, and deleting feedback.
 */
class FeedbackController extends Controller
{
    use ApiResponse;

    protected FeedbackService $feedbackService;

    /**
     * Inject the FeedbackService into the controller.
     */
    public function __construct(FeedbackService $feedbackService)
    {
        $this->feedbackService = $feedbackService;
    }

    /**
     * GET /feedbacks
     * Allows optional filters such as:
     * - start_date, end_date (date range)
     * - rating
     * - per_page (pagination)
     *
     * Example:
     * GET /feedbacks?start_date=2025-02-01&end_date=2025-02-28&rating=5&per_page=10
     */
    public function index(Request $request): JsonResponse
    {
        // Retrieve filter parameters
        $startDate = $request->get('start_date');
        $endDate   = $request->get('end_date');
        $rating    = $request->get('rating');
        $perPage   = $request->get('per_page');

        // Use the service method that applies filters
        $feedbacks = $this->feedbackService->getFilteredFeedbacks($startDate, $endDate, $rating, $perPage);

        return $this->successResponse(
            FeedbackResource::collection($feedbacks),
            'Feedback list retrieved successfully'
        );
    }

    /**
     * POST /feedbacks
     * Store a new feedback.
     */
    public function store(StoreFeedbackRequest $request): JsonResponse
    {
        $newFeedback = $this->feedbackService->createFeedback($request->validated());

        return $this->successResponse(
            new FeedbackResource($newFeedback),
            'Feedback created successfully',
            201
        );
    }

    /**
     * DELETE /feedbacks/{id}
     * Delete a feedback by ID.
     */
    public function destroy($id): JsonResponse
    {
        $deleted = $this->feedbackService->deleteFeedback($id);

        if ($deleted) {
            return $this->successResponse(null, 'Feedback deleted successfully', 200);
        }
        return $this->errorResponse('Feedback not found', 404);
    }

    /**
     * POST /assign/{feedbackId}/{manager_id}
     * Assign a feedback to a manager.
     */
    public function assignToManager($feedbackId, $manager_id): JsonResponse
    {
        $assigned = $this->feedbackService->assignFeedbackToManager($feedbackId, $manager_id);

        if ($assigned) {
            return $this->successResponse(null, 'Feedback assigned to manager successfully', 200);
        }
        return $this->errorResponse('Feedback or Manager not found', 404);
    }
}
