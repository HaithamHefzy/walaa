<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\CouponService;
use App\Http\Requests\Codes\{StoreCouponRequest,UpdateCouponRequest,CodeRequest};
use App\Http\Resources\CouponResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class CouponsController extends Controller
{
    use ApiResponse;

    protected CouponService $couponService;

    /**
     * Inject the CouponService into the controller.
     */
    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    /**
     * Retrieve a paginated list of coupons.
     */
    public function index(): JsonResponse
    {
        $coupons = $this->couponService->getAllCoupons(request()->get('per_page'));
        return $this->successResponse(CouponResource::collection($coupons), 'Coupons retrieved successfully');
    }

    /**
     * Store a new coupon.
     */
    public function store(StoreCouponRequest $request): JsonResponse
    {
        $coupon = $this->couponService->createCoupon($request->validated());

        return $this->successResponse(new CouponResource($coupon), 'Coupon created successfully', 201);
    }

    /**
     * Retrieve a specific coupon by ID.
     */
    public function show($id): JsonResponse
    {
        $coupon = $this->couponService->getCouponById($id);
        return $coupon
            ? $this->successResponse(new CouponResource($coupon), 'Coupon retrieved successfully')
            : $this->errorResponse('Coupon not found', 404);
    }

    /**
     * Update an existing coupon.
     */
    public function update(UpdateCouponRequest $request, $id): JsonResponse
    {
        $coupon = $this->couponService->updateCoupon($id, $request->validated());
        return $coupon
            ? $this->successResponse(new CouponResource($coupon), 'Coupon updated successfully')
            : $this->errorResponse('Coupon not found', 404);
    }

    /**
     * Update existing coupon status.
     */
    public function useTheCoupon(CodeRequest $codeRequest)
    {
        $response = $this->couponService->useTheCoupon($codeRequest->validated());
        return $response
            ? $this->successResponse([],$response)
            : $this->errorResponse('There are no coupons for this code', 404);
    }

    /**
     * Delete a coupon by ID.
     */
    public function destroy($id): JsonResponse
    {
        return $this->couponService->deleteCoupon($id)
            ? $this->successResponse([], 'Coupon deleted successfully', 200)
            : $this->errorResponse('Coupon not found', 404);
    }
}