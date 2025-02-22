<?php

namespace App\Services;

use App\Repositories\GiftCodeRepository;

class GiftCodeService
{
    protected GiftCodeRepository $giftCodeRepository;

    /**
     * Inject the GiftCodeRepository into the service.
     */
    public function __construct(GiftCodeRepository $giftCodeRepository)
    {
        $this->giftCodeRepository = $giftCodeRepository;
    }

    /**
     * Retrieve all gift codes with pagination.
     */
    public function getAllCodes($perPage)
    {
        return $this->giftCodeRepository->getAllCodes($perPage);
    }

    /**
     * Retrieve a single gift code by ID.
     */
    public function getGiftCodeById($id)
    {
        return $this->giftCodeRepository->getGiftCodeById($id);
    }

    /**
     * Create a new gift code.
     */
    public function createGiftCode($data)
    {
        return $this->giftCodeRepository->createGiftCode($data);
    }

    /**
     * Update an existing gift code.
     */
    public function updateGiftCode($id, $data)
    {
        return $this->giftCodeRepository->updateGiftCode($id,$data);
    }

    /**
     * Delete a gift code by ID.
     */
    public function deleteGiftCode($id)
    {
        return $this->giftCodeRepository->deleteGiftCode($id);
    }
}