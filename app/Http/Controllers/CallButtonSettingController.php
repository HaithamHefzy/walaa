<?php

namespace App\Http\Controllers;

use App\Services\CallButtonSettingService;
use App\Http\Requests\StoreCallButtonRequest;
use App\Http\Resources\CallButtonSettingResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * CallButtonSettingController
 * Manages CRUD for call button settings.
 */
class CallButtonSettingController extends Controller
{
    use ApiResponse;

    protected CallButtonSettingService $buttonService;

    public function __construct(CallButtonSettingService $buttonService)
    {
        $this->buttonService = $buttonService;
    }

    /**
     * Retrieve all call button settings with optional pagination (?per_page=XX).
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page');
        $buttons = $this->buttonService->getAllCallButtons($perPage);

        return $this->successResponse(
            CallButtonSettingResource::collection($buttons),
            'Call button settings retrieved successfully'
        );
    }

    /**
     * Create a new call button setting.
     */
    public function store(StoreCallButtonRequest $request): JsonResponse
    {
        $button = $this->buttonService->createCallButton($request->validated());

        return $this->successResponse(
            new CallButtonSettingResource($button),
            'Call button setting created successfully',
            201
        );
    }

    /**
     * Delete a call button setting by ID.
     */
    public function destroy($id): JsonResponse
    {
        $deleted = $this->buttonService->deleteCallButton($id);
        if ($deleted) {
            return $this->successResponse(null, 'Call button setting deleted successfully', 200);
        }
        return $this->errorResponse('Call button setting not found', 404);
    }

    /**
     * Example endpoint to find a suitable button for a certain number of people.
     */
    public function findSuitable(Request $request): JsonResponse
    {
        $peopleCount = $request->get('people');
        if (!$peopleCount) {
            return $this->errorResponse('people parameter is required', 400);
        }

        $button = $this->buttonService->findSuitableButton($peopleCount);
        if (!$button) {
            return $this->errorResponse('No suitable call button found', 404);
        }
        return $this->successResponse(new CallButtonSettingResource($button), 'Suitable call button found');
    }
}
