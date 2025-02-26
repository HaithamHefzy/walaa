<?php

namespace App\Services;

use App\Repositories\MarketingMessageRepository;

class MarketingMessageService
{
    protected MarketingMessageRepository $marketingMessageRepository;

    /**
     * Inject the MarketingMessageRepository into the service.
     */
    public function __construct(MarketingMessageRepository $marketingMessageRepository)
    {
        $this->marketingMessageRepository = $marketingMessageRepository;
    }

    /**
     * Retrieve all marketing messages with pagination.
     */
    public function getAllMarketingMessages($perPage)
    {
        return $this->marketingMessageRepository->getAllMarketingMessages($perPage);
    }

    /**
     * Retrieve a single marketing message by ID.
     */
    public function getMarketingMessageById($id)
    {
        return $this->marketingMessageRepository->getMarketingMessageById($id);
    }

    /**
     * Create a new marketing message.
     */
    public function createMarketingMessage($data)
    {
        return $this->marketingMessageRepository->createMarketingMessage($data);
    }

    /**
     * Update an existing marketing message.
     */
    public function updateMarketingMessage($id, $data)
    {
        return $this->marketingMessageRepository->updateMarketingMessage($id,$data);
    }

    /**
     * Delete a marketing message by ID.
     */
    public function deleteMarketingMessage($id)
    {
        return $this->marketingMessageRepository->deleteMarketingMessage($id);
    }
}