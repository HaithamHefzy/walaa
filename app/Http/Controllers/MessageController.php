<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\MarketingMessageService;
use App\Http\Requests\Marketing\{StoreMarketingMessageRequest,UpdateMarketingMessageRequest};
use App\Http\Resources\MarketingMessageResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    use ApiResponse;

    protected MarketingMessageService $marketingMessageService;

    /**
     * Inject the MarketingMessageService into the controller.
     */
    public function __construct(MarketingMessageService $marketingMessageService)
    {
        $this->marketingMessageService = $marketingMessageService;
    }

    /**
     * Retrieve a paginated list of marketing messages.
     */
    public function index(): JsonResponse
    {
        $marketingMessages = $this->marketingMessageService->getAllMarketingMessages(request()->get('per_page'));
        return $this->successResponse(MarketingMessageResource::collection($marketingMessages), 'Marketing messages retrieved successfully');
    }

    /**
     * Store a new marketing message.
     */
    public function store(StoreMarketingMessageRequest $request): JsonResponse
    {
        $marketingMessage = $this->marketingMessageService->createMarketingMessage($request->validated());

        return $this->successResponse(new MarketingMessageResource($marketingMessage), 'Marketing message created successfully', 201);
    }

    /**
     * Retrieve a specific marketing message by ID.
     */
    public function show($id): JsonResponse
    {
        $marketingMessage = $this->marketingMessageService->getMarketingMessageById($id);
        return $marketingMessage
            ? $this->successResponse(new MarketingMessageResource($marketingMessage), 'Marketing message retrieved successfully')
            : $this->errorResponse('Marketing message not found', 404);
    }

    /**
     * Update an existing marketing message.
     */
    public function update(UpdateMarketingMessageRequest $request, $id): JsonResponse
    {
        $marketingMessage = $this->marketingMessageService->updateMarketingMessage($id, $request->validated());
        return $marketingMessage
            ? $this->successResponse(new MarketingMessageResource($marketingMessage), 'Marketing message updated successfully')
            : $this->errorResponse('Marketing message not found', 404);
    }

    /**
     * Delete a marketing message by ID.
     */
    public function destroy($id): JsonResponse
    {
        return $this->marketingMessageService->deleteMarketingMessage($id)
            ? $this->successResponse([], 'Marketing message deleted successfully', 200)
            : $this->errorResponse('Marketing message not found', 404);
    }
}