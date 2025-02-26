<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\MarketingMessageSettingService;
use App\Http\Requests\Marketing\{StoreMarketingMessageSettingRequest,UpdateMarketingMessageSettingRequest};
use App\Http\Resources\MarketingMessageSettingResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class MessageSettingController extends Controller
{
    use ApiResponse;

    protected MarketingMessageSettingService $marketingMessageSettingService;

    /**
     * Inject the MarketingMessageSettingService into the controller.
     */
    public function __construct(MarketingMessageSettingService $marketingMessageSettingService)
    {
        $this->marketingMessageSettingService = $marketingMessageSettingService;
    }

    /**
     * Retrieve a paginated list of marketing message settings.
     */
    public function index(): JsonResponse
    {
        $marketingMessageSettings = $this->marketingMessageSettingService->getAllMarketingMessageSettings(request()->get('per_page'));
        return $this->successResponse(MarketingMessageSettingResource::collection($marketingMessageSettings), 'Marketing message settings retrieved successfully');
    }

    /**
     * Store a new marketing message setting.
     */
    public function store(StoreMarketingMessageSettingRequest $request): JsonResponse
    {
        $marketingMessageSetting = $this->marketingMessageSettingService->createMarketingMessageSetting($request->validated());

        return $this->successResponse(new MarketingMessageSettingResource($marketingMessageSetting), 'Marketing message setting created successfully', 201);
    }

    /**
     * Retrieve a specific marketing message setting by ID.
     */
    public function show($id): JsonResponse
    {
        $marketingMessageSetting = $this->marketingMessageSettingService->getMarketingMessageSettingById($id);
        return $marketingMessageSetting
            ? $this->successResponse(new MarketingMessageSettingResource($marketingMessageSetting), 'Marketing message setting retrieved successfully')
            : $this->errorResponse('Marketing message setting not found', 404);
    }

    /**
     * Update an existing marketing message setting.
     */
    public function update(UpdateMarketingMessageSettingRequest $request, $id): JsonResponse
    {
        $marketingMessageSetting = $this->marketingMessageSettingService->updateMarketingMessageSetting($id, $request->validated());
        return $marketingMessageSetting
            ? $this->successResponse(new MarketingMessageSettingResource($marketingMessageSetting), 'Marketing message setting updated successfully')
            : $this->errorResponse('Marketing message not found', 404);
    }

    /**
     * Delete a marketing message setting by ID.
     */
    public function destroy($id): JsonResponse
    {
        return $this->marketingMessageSettingService->deleteMarketingMessageSetting($id)
            ? $this->successResponse([], 'Marketing message setting deleted successfully', 200)
            : $this->errorResponse('Marketing message setting not found', 404);
    }
}