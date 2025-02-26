<?php

namespace App\Repositories;

use App\Models\MarketingMessageSetting;
use App\Helpers\HandleUpload;

class MarketingMessageSettingRepository
{
    public function getAllMarketingMessageSettings($perPage)
    {
        return is_null($perPage) ? MarketingMessageSetting::get() : MarketingMessageSetting::paginate($perPage);
    }

    public function createMarketingMessageSetting(array $data)
    {
        $data['attachment_path'] = isset($data['attachment_path']) ? HandleUpload::uploadFile($data['attachment_path'],'attachments') : NULL;
        return MarketingMessageSetting::create($data);
    }

    public function getMarketingMessageSettingById($id)
    {
        return MarketingMessageSetting::find($id);
    }

    public function updateMarketingMessageSetting($id, array $data)
    {
        $marketingMessageSetting = MarketingMessageSetting::find($id);
        if ($marketingMessageSetting) {
            $data['attachment_path'] = isset($data['attachment_path']) ? HandleUpload::uploadFile($data['attachment_path'],'attachments') : $marketingMessageSetting->attachment_path;
            $marketingMessageSetting->update($data);
            return $marketingMessageSetting;
        }
        return null;
    }

    public function deleteMarketingMessageSetting($id)
    {
        $marketingMessageSetting = MarketingMessageSetting::find($id);
        return $marketingMessageSetting ? $marketingMessageSetting->delete() : false;
    }
}