<?php

namespace App\Repositories;

use App\Models\Coupon;

class CouponRepository
{
    /**
     * Retrieve all coupons with pagination.
     */
    public function getAllCoupons($perPage)
    {
        return is_null($perPage) ? Coupon::get() : Coupon::paginate($perPage);
    }

    /**
     * Create a new coupon.
     */
    public function createCoupon(array $data)
    {
        return Coupon::create($data);
    }

    /**
     * Retrieve a coupon by ID.
     */
    public function getCouponById($id)
    {
        return Coupon::find($id);
    }

    /**
     * Update a coupon.
     */
    public function updateCoupon($id, array $data)
    {
        $coupon = Coupon::find($id);
        if ($coupon) {
            $coupon->update($data);
            return $coupon;
        }
        return null;
    }

    /**
     * Delete a coupon by ID.
     */
    public function deleteCoupon($id)
    {
        $coupon = Coupon::find($id);
        return $coupon ? $coupon->delete() : false;
    }
}