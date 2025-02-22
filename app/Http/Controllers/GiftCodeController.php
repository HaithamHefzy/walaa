<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\GiftCodeService;
use App\Http\Requests\Gift\{StoreGiftCodeRequest,UpdateGiftCodeRequest};
use App\Http\Resources\GiftCodeResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class GiftCodeController extends Controller
{
    use ApiResponse;

    protected GiftCodeService $giftCodeService;

    /**
     * Inject the GiftCodeService into the controller.
     */
    public function __construct(GiftCodeService $giftCodeService)
    {
        $this->giftCodeService = $giftCodeService;
    }

    /**
     * Retrieve a paginated list of codes.
     */
    public function index(): JsonResponse
    {
        $codes = $this->giftCodeService->getAllCodes(request()->get('per_page'));
        return $this->successResponse(GiftCodeResource::collection($codes), 'Codes retrieved successfully');
    }

    /**
     * Store a new gift_code.
     */
    public function store(StoreGiftCodeRequest $request): JsonResponse
    {
        $gift_code = $this->giftCodeService->createGiftCode($request->validated());

        return $this->successResponse(new GiftCodeResource($gift_code), 'Gift code created successfully', 201);
    }

    /**
     * Retrieve a specific gift_code by ID.
     */
    public function show($id): JsonResponse
    {
        $gift_code = $this->giftCodeService->getGiftCodeById($id);
        return $gift_code
            ? $this->successResponse(new GiftCodeResource($gift_code), 'Gift code retrieved successfully')
            : $this->errorResponse('Gift code not found', 404);
    }

    /**
     * Update an existing gift_code.
     */
    public function update(UpdateGiftCodeRequest $request, $id): JsonResponse
    {
        $gift_code = $this->giftCodeService->updateGiftCode($id, $request->validated());
        return $gift_code
            ? $this->successResponse(new GiftCodeResource($gift_code), 'Gift code updated successfully')
            : $this->errorResponse('Gift code not found', 404);
    }

    /**
     * Delete a gift code by ID.
     */
    public function destroy($id): JsonResponse
    {
        return $this->giftCodeService->deleteGiftCode($id)
            ? $this->successResponse([], 'Gift code deleted successfully', 200)
            : $this->errorResponse('Gift code not found', 404);
    }
}