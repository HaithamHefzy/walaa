<?php

namespace App\Http\Controllers;

use App\Services\CallButtonSettingService;
use App\Http\Resources\CallButtonSettingResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * CallButtonSettingController
 * Manages reading and updating call button settings (A, B, C),
 * allowing multiple buttons to be updated at once.
 */
class CallButtonSettingController extends Controller
{
    use ApiResponse;

    protected CallButtonSettingService $buttonService;

    /**
     * Inject the CallButtonSettingService into the controller.
     *
     * @param CallButtonSettingService $buttonService
     */
    public function __construct(CallButtonSettingService $buttonService)
    {
        $this->buttonService = $buttonService;
    }

    /**
     * GET /call-button-settings
     * Retrieve all call button settings with optional pagination (?per_page=XX).
     *
     * @param Request $request
     * @return JsonResponse
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
     * GET /call-button-settings/suitable?people=XXX
     * Example for finding a suitable button for a given number of people.
     *
     * @param Request $request
     * @return JsonResponse
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

    /**
     * POST /call-button-settings/update-multiple
     * Example Body: { "A": 3, "B": 5, "C": 7 }
     * Updates max_people for each button type in a single request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateMultiple(Request $request): JsonResponse
    {
        // Validate input: each key (A, B, C) must be an integer greater than or equal to 1
        $validated = $request->validate([
            'A' => 'required|integer|min:1',
            'B' => 'required|integer|min:1',
            'C' => 'required|integer|min:1',
        ]);

        // Update the call button settings for A, B, and C using the service
        $this->buttonService->updateMultipleButtons($validated);

        // Retrieve the updated call button settings
        $updatedButtons = $this->buttonService->getAllCallButtons();

        // Return the updated settings as a success response using the resource collection
        return $this->successResponse(
            \App\Http\Resources\CallButtonSettingResource::collection($updatedButtons),
            'Call button settings updated successfully',
            200
        );
    }

}
