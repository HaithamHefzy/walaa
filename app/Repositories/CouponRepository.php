<?php

namespace App\Repositories;

use App\Models\Coupon;
use Carbon\Carbon;

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
     * Update coupon status.
    */
    public function useTheCoupon($data)
    {
        $coupon = Coupon::where('recipient_phone',$data['recipient_phone'])->whereHas('discountCode',function($code) use($data){
            $code->where('code',$data['code']);
        })->first();

        if ($coupon) {
            if($coupon->usage_status == 'used'){
                return 'The coupon has already been used before';
            }else{

                $discountCode = $coupon->discountCode;
                $createdAt = Carbon::parse($discountCode->created_at);
                $now = Carbon::now();

                $canUseAfter = $createdAt->copy()->addHours($discountCode->validity_after_hours);
                $canUseNow = $now->greaterThanOrEqualTo($canUseAfter); 

                $expiresAt = $createdAt->copy()->addDays($discountCode->validity_days);
                $isStillValid = $now->lessThanOrEqualTo($expiresAt);

                if (!$canUseNow) {
                    return "The coupon code is not yet available for use.";
                } elseif (!$isStillValid) {
                    return "The coupon code has expired.";
                } else {
                    $coupon->update(['usage_status' => 'used']);
                    return 'The coupon has been used successfully';
                }
            }
        }
        return false;
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