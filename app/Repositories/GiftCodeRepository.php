<?php

namespace App\Repositories;

use App\Models\GiftCode;

class GiftCodeRepository
{
    /**
     * Retrieve all gift codes with pagination.
     */
    public function getAllCodes($perPage)
    {
        return is_null($perPage) ? GiftCode::get() : GiftCode::paginate($perPage);
    }

    /**
     * Create a new gift code.
     */
    public function createGiftCode(array $data)
    {
        return GiftCode::create($data);
    }

    /**
     * Retrieve a gift code by ID.
     */
    public function getGiftCodeById($id)
    {
        return GiftCode::find($id);
    }

    /**
     * Update a gift code.
     */
    public function updateGiftCode($id, array $data)
    {
        $giftCode = GiftCode::find($id);
        if ($giftCode) {
            $giftCode->update($data);
            return $giftCode;
        }
        return null;
    }

    /**
     * Delete a gift code by ID.
     */
    public function deleteGiftCode($id)
    {
        $giftCode = GiftCode::find($id);
        return $giftCode ? $giftCode->delete() : false;
    }
}