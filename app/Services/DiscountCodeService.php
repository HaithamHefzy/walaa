<?php

namespace App\Services;

use App\Repositories\DiscountCodeRepository;

class DiscountCodeService
{
    protected DiscountCodeRepository $discountCodeRepository;

    /**
     * Inject the DiscountCodeRepository into the service.
     */
    public function __construct(DiscountCodeRepository $discountCodeRepository)
    {
        $this->discountCodeRepository = $discountCodeRepository;
    }

    /**
     * Retrieve all discount codes with pagination.
     */
    public function getAllDiscountCodes($perPage)
    {
        return $this->discountCodeRepository->getAllDiscountCodes($perPage);
    }

    /**
     * Retrieve a single discount code by ID.
     */
    public function getDiscountCodeById($id)
    {
        return $this->discountCodeRepository->getDiscountCodeById($id);
    }

    /**
     * Create a new discount code.
     */
    public function createDiscountCode($data)
    {
        return $this->discountCodeRepository->createDiscountCode($data);
    }

    /**
     * Update an existing discount code.
     */
    public function updateDiscountCode($id, $data)
    {
        return $this->discountCodeRepository->updateDiscountCode($id,$data);
    }

    /**
     * Delete a discount code by ID.
     */
    public function deleteDiscountCode($id)
    {
        return $this->discountCodeRepository->deleteDiscountCode($id);
    }
}