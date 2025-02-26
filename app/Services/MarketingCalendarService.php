<?php

namespace App\Services;

use App\Repositories\MarketingCalendarRepository;

class MarketingCalendarService
{
    protected MarketingCalendarRepository $marketingCalendarRepository;

    /**
     * Inject the MarketingCalendarRepository into the service.
     */
    public function __construct(MarketingCalendarRepository $marketingCalendarRepository)
    {
        $this->marketingCalendarRepository = $marketingCalendarRepository;
    }

    /**
     * Retrieve all marketing calendars with pagination.
     */
    public function getAllMarketingCalendars($perPage)
    {
        return $this->marketingCalendarRepository->getAllMarketingCalendars($perPage);
    }

    /**
     * Retrieve all coming marketing calendars with pagination.
     */
    public function getComingMarketingCalendars($perPage)
    {
        return $this->marketingCalendarRepository->getComingMarketingCalendars($perPage);
    }

    /**
     * Retrieve all finished marketing calendars with pagination.
     */
    public function getFinishedMarketingCalendars($perPage)
    {
        return $this->marketingCalendarRepository->getFinishedMarketingCalendars($perPage);
    }

    /**
     * Retrieve a single marketing calendar by ID.
     */
    public function getMarketingCalendarById($id)
    {
        return $this->marketingCalendarRepository->getMarketingCalendarById($id);
    }

    /**
     * Create a new marketing calendar.
     */
    public function createMarketingCalendar($data)
    {
        return $this->marketingCalendarRepository->createMarketingCalendar($data);
    }

    /**
     * Update an existing marketing calendar.
     */
    public function updateMarketingCalendar($id, $data)
    {
        return $this->marketingCalendarRepository->updateMarketingCalendar($id,$data);
    }

    /**
     * Delete a marketing calendar by ID.
     */
    public function deleteMarketingCalendar($id)
    {
        return $this->marketingCalendarRepository->deleteMarketingCalendar($id);
    }
}