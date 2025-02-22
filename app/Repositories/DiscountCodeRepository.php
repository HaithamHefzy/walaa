<?php

namespace App\Repositories;

use App\Models\DiscountCode;

class DiscountCodeRepository
{
    /**
     * Retrieve all discount codes with pagination.
     */
    public function getAllDiscountCodes($perPage)
    {
        return is_null($perPage) ? DiscountCode::get() : DiscountCode::paginate($perPage);
    }

    /**
     * Create a new discount code.
     */
    public function createDiscountCode(array $data)
    {
        return DiscountCode::create($data);
    }

    /**
     * Retrieve a discount code by ID.
     */
    public function getDiscountCodeById($id)
    {
        return DiscountCode::find($id);
    }

    /**
     * Update a discount code.
     */
    public function updateDiscountCode($id, array $data)
    {
        $discountCode = DiscountCode::find($id);
        if ($discountCode) {
            $discountCode->update($data);
            return $discountCode;
        }
        return null;
    }

    /**
     * Delete a discount code by ID.
     */
    public function deleteDiscountCode($id)
    {
        $discountCode = DiscountCode::find($id);
        return $discountCode ? $discountCode->delete() : false;
    }
}