<?php

namespace App\Services;

use App\Repositories\MarketingMessageSettingRepository;

class MarketingMessageSettingService
{
    protected MarketingMessageSettingRepository $marketingMessageSettingRepository;

    /**
     * Inject the MarketingMessageSettingRepository into the service.
     */
    public function __construct(MarketingMessageSettingRepository $marketingMessageSettingRepository)
    {
        $this->marketingMessageSettingRepository = $marketingMessageSettingRepository;
    }

    /**
     * Retrieve all marketing message settings with pagination.
     */
    public function getAllMarketingMessageSettings($perPage)
    {
        return $this->marketingMessageSettingRepository->getAllMarketingMessageSettings($perPage);
    }

    /**
     * Retrieve a single marketing message setting by ID.
     */
    public function getMarketingMessageSettingById($id)
    {
        return $this->marketingMessageSettingRepository->getMarketingMessageSettingById($id);
    }

    /**
     * Create a new marketing message setting.
     */
    public function createMarketingMessageSetting($data)
    {
        return $this->marketingMessageSettingRepository->createMarketingMessageSetting($data);
    }

    /**
     * Update an existing marketing message setting.
     */
    public function updateMarketingMessageSetting($id, $data)
    {
        return $this->marketingMessageSettingRepository->updateMarketingMessageSetting($id,$data);
    }

    /**
     * Delete a marketing message setting by ID.
     */
    public function deleteMarketingMessageSetting($id)
    {
        return $this->marketingMessageSettingRepository->deleteMarketingMessageSetting($id);
    }
}