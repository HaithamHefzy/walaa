<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\DiscountCodeService;
use App\Http\Requests\Codes\{StoreDiscountCodeRequest,UpdateDiscountCodeRequest};
use App\Http\Resources\DiscountCodeResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class DiscountCodeController extends Controller
{
    use ApiResponse;

    protected DiscountCodeService $discountCodeService;

    /**
     * Inject the DiscountCodeService into the controller.
     */
    public function __construct(DiscountCodeService $discountCodeService)
    {
        $this->discountCodeService = $discountCodeService;
    }

    /**
     * Retrieve a paginated list of discount codes.
     */
    public function index(): JsonResponse
    {
        $discountCodes = $this->discountCodeService->getAllDiscountCodes(request()->get('per_page'));
        return $this->successResponse(DiscountCodeResource::collection($discountCodes), 'Discount codes retrieved successfully');
    }

    /**
     * Store a new discount code.
     */
    public function store(StoreDiscountCodeRequest $request): JsonResponse
    {
        $discountCode = $this->discountCodeService->createDiscountCode($request->validated());

        return $this->successResponse(new DiscountCodeResource($discountCode), 'Discount code created successfully', 201);
    }

    /**
     * Retrieve a specific discount code by ID.
     */
    public function show($id): JsonResponse
    {
        $discountCode = $this->discountCodeService->getDiscountCodeById($id);
        return $discountCode
            ? $this->successResponse(new DiscountCodeResource($discountCode), 'Discount Code retrieved successfully')
            : $this->errorResponse('Discount code not found', 404);
    }

    /**
     * Update an existing discount code.
     */
    public function update(UpdateDiscountCodeRequest $request, $id): JsonResponse
    {
        $discountCode = $this->discountCodeService->updateDiscountCode($id, $request->validated());
        return $discountCode
            ? $this->successResponse(new DiscountCodeResource($discountCode), 'Discount code updated successfully')
            : $this->errorResponse('Discount code not found', 404);
    }

    /**
     * Delete a discount code by ID.
     */
    public function destroy($id): JsonResponse
    {
        return $this->discountCodeService->deleteDiscountCode($id)
            ? $this->successResponse([], 'Discount code deleted successfully', 200)
            : $this->errorResponse('Discount code not found', 404);
    }
}