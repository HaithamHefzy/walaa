<?php

namespace App\Http\Controllers;

use App\Services\FeedbackManagerService;
use App\Http\Requests\StoreFeedbackManagerRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeedbackManagerController extends Controller
{
    use ApiResponse;

    protected FeedbackManagerService $managerService;

    /**
     * Inject the FeedbackManagerService.
     */
    public function __construct(FeedbackManagerService $managerService)
    {
        $this->managerService = $managerService;
    }

    /**
     * GET /feedback-managers
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page');

        $managers = $this->managerService->getAllManagers($perPage);

        return $this->successResponse($managers, 'List of feedback managers');
    }

    /**
     * POST /feedback-managers
     */
    public function store(StoreFeedbackManagerRequest $request): JsonResponse
    {
        $manager = $this->managerService->createManager($request->validated());

        return $this->successResponse($manager, 'Feedback manager created successfully', 201);
    }

    /**
     * DELETE /feedback-managers/{id}
     */
    public function destroy($id): JsonResponse
    {
        $deleted = $this->managerService->deleteManager($id);

        if ($deleted) {
            return $this->successResponse(null, 'Feedback manager deleted successfully', 200);
        }
        return $this->errorResponse('Feedback manager not found', 404);
    }
}
