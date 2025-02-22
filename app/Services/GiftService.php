<?php

namespace App\Services;

use App\Repositories\GiftRepository;

class GiftService
{
    protected GiftRepository $giftRepository;

    /**
     * Inject the GiftRepository into the service.
     */
    public function __construct(GiftRepository $giftRepository)
    {
        $this->giftRepository = $giftRepository;
    }

    /**
     * Retrieve all gifts with pagination.
     */
    public function getAllGifts($perPage)
    {
        return $this->giftRepository->getAllGifts($perPage);
    }

    /**
     * Retrieve a single gift by ID.
     */
    public function getGiftById($id)
    {
        return $this->giftRepository->getGiftById($id);
    }

    /**
     * Create a new gift.
     */
    public function createGift($data)
    {
        return $this->giftRepository->createGift($data);
    }

    /**
     * Update an existing gift.
     */
    public function updateGift($id, $data)
    {
        return $this->giftRepository->updateGift($id,$data);
    }

    /**
     * Delete a gift by ID.
     */
    public function deleteGift($id)
    {
        return $this->giftRepository->deleteGift($id);
    }
}