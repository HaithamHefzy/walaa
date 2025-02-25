<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\GiftService;
use App\Http\Requests\Gift\{StoreGiftRequest,UpdateGiftRequest,CodeRequest};
use App\Http\Resources\GiftResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class GiftController extends Controller
{
    use ApiResponse;

    protected GiftService $giftService;

    /**
     * Inject the GiftService into the controller.
     */
    public function __construct(GiftService $giftService)
    {
        $this->giftService = $giftService;
    }

    /**
     * Retrieve a paginated list of gifts.
     */
    public function index(): JsonResponse
    {
        $gifts = $this->giftService->getAllGifts(request()->get('per_page'));
        return $this->successResponse(GiftResource::collection($gifts), 'Gifts retrieved successfully');
    }

    /**
     * Store a new gift.
     */
    public function store(StoreGiftRequest $request): JsonResponse
    {
        $gift = $this->giftService->createGift($request->validated());

        return $this->successResponse(new GiftResource($gift), 'Gift created successfully', 201);
    }

    /**
     * Retrieve a specific gift by ID.
     */
    public function show($id): JsonResponse
    {
        $gift = $this->giftService->getGiftById($id);
        return $gift
            ? $this->successResponse(new GiftResource($gift), 'Gift retrieved successfully')
            : $this->errorResponse('Gift not found', 404);
    }

    /**
     * Update an existing gift.
     */
    public function update(UpdateGiftRequest $request, $id): JsonResponse
    {
        $gift = $this->giftService->updateGift($id, $request->validated());
        return $gift
            ? $this->successResponse(new GiftResource($gift), 'Gift updated successfully')
            : $this->errorResponse('Gift not found', 404);
    }

    /**
     * Update existing gift status.
     */
    public function useTheGift(CodeRequest $codeRequest)
    {
        $response = $this->giftService->useTheGift($codeRequest->validated());
        return $response
            ? $this->successResponse([],$response)
            : $this->errorResponse('There are no gifts for this code', 404);
    }

    /**
     * Delete a gift by ID.
     */
    public function destroy($id): JsonResponse
    {
        return $this->giftService->deleteGift($id)
            ? $this->successResponse([], 'Gift deleted successfully', 200)
            : $this->errorResponse('Gift not found', 404);
    }
}