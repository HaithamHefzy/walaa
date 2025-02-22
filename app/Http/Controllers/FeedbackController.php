<?php

namespace App\Http\Controllers;

use App\Services\FeedbackService;
use App\Http\Requests\StoreFeedbackRequest;
use App\Http\Resources\FeedbackResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
     * Retrieve all feedbacks with optional pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page');

        $feedbacks = $this->feedbackService->getAllFeedbacks($perPage);

        return $this->successResponse(
            FeedbackResource::collection($feedbacks),
            'Feedback list retrieved successfully'
        );
    }

    /**
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
