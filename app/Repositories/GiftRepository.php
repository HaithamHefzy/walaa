<?php

namespace App\Repositories;

use App\Models\Gift;

class GiftRepository
{
    /**
     * Retrieve all gifts with pagination.
     */
    public function getAllGifts($perPage)
    {
        return is_null($perPage) ? Gift::get() : Gift::paginate($perPage);
    }

    /**
     * Create a new gift.
     */
    public function createGift(array $data)
    {
        return Gift::create($data);
    }

    /**
     * Retrieve a gift by ID.
     */
    public function getGiftById($id)
    {
        return Gift::find($id);
    }

    /**
     * Update a gift.
     */
    public function updateGift($id, array $data)
    {
        $gift = Gift::find($id);
        if ($gift) {
            $gift->update($data);
            return $gift;
        }
        return null;
    }

    /**
     * Delete a gift by ID.
     */
    public function deleteGift($id)
    {
        $gift = Gift::find($id);
        return $gift ? $gift->delete() : false;
    }
}