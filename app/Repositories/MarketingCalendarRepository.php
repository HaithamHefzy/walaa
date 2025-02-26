<?php

namespace App\Repositories;

use App\Models\MarketingCalendar;

class MarketingCalendarRepository
{
    public function getAllMarketingCalendars($perPage)
    {
        return is_null($perPage) ? MarketingCalendar::get() : MarketingCalendar::paginate($perPage);
    }

    public function getComingMarketingCalendars($perPage)
    {
        return is_null($perPage) ? MarketingCalendar::where('event_date', '>', now())->get() : MarketingCalendar::where('event_date', '>', now())->paginate($perPage);
    }

    public function getFinishedMarketingCalendars($perPage)
    {
        return is_null($perPage) ? MarketingCalendar::where('event_date', '<', now())->get() : MarketingCalendar::where('event_date', '<', now())->paginate($perPage);
    }

    public function createMarketingCalendar(array $data)
    {
        return MarketingCalendar::create($data);
    }

    public function getMarketingCalendarById($id)
    {
        return MarketingCalendar::find($id);
    }

    public function updateMarketingCalendar($id, array $data)
    {
        $marketingCalendar = MarketingCalendar::find($id);
        if ($marketingCalendar) {
            $marketingCalendar->update($data);
            return $marketingCalendar;
        }
        return null;
    }

    public function deleteMarketingCalendar($id)
    {
        $marketingCalendar = MarketingCalendar::find($id);
        return $marketingCalendar ? $marketingCalendar->delete() : false;
    }
}