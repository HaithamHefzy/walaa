<?php

namespace App\Services;

use App\Repositories\CouponRepository;

class CouponService
{
    protected CouponRepository $couponRepository;

    /**
     * Inject the CouponRepository into the service.
     */
    public function __construct(CouponRepository $couponRepository)
    {
        $this->couponRepository = $couponRepository;
    }

    /**
     * Retrieve all coupons with pagination.
     */
    public function getAllCoupons($perPage)
    {
        return $this->couponRepository->getAllCoupons($perPage);
    }

    /**
     * Retrieve a single coupon by ID.
     */
    public function getCouponById($id)
    {
        return $this->couponRepository->getCouponById($id);
    }

    /**
     * Create a new coupon.
     */
    public function createCoupon($data)
    {
        return $this->couponRepository->createCoupon($data);
    }

    /**
     * Update an existing coupon.
     */
    public function updateCoupon($id, $data)
    {
        return $this->couponRepository->updateCoupon($id,$data);
    }

    /**
     * change existing coupon status.
     */
    public function useTheCoupon($data)
    {
        return $this->couponRepository->useTheCoupon($data);
    }

    /**
     * Delete a coupon by ID.
     */
    public function deleteCoupon($id)
    {
        return $this->couponRepository->deleteCoupon($id);
    }
}