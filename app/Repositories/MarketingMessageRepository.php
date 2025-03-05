<?php

namespace App\Repositories;

use App\Models\MarketingMessage;
use App\Helpers\HandleUpload;
use App\Models\Client;
use App\Helpers\SendMessageHelper;

class MarketingMessageRepository
{
    public function getAllMarketingMessages($perPage)
    {
        return is_null($perPage) ? MarketingMessage::get() : MarketingMessage::paginate($perPage);
    }

    public function createMarketingMessage(array $data)
    {
        $ids = is_array($data['client_ids']) ? $data['client_ids'] : json_decode($data['client_ids']);
        $data['client_ids'] = is_array($data['client_ids']) ? json_encode($data['client_ids']) : $data['client_ids'];
        $data['delivery_method'] = is_array($data['delivery_method']) ? json_encode($data['delivery_method']) : $data['delivery_method'];
        $data['attachment_path'] = isset($data['attachment_path']) ? HandleUpload::uploadFile($data['attachment_path'],'attachments') : NULL;

        $clients = $data['send_to'] == 'all' ? Client::all() : Client::whereIn('id',$ids)->get();

        foreach($clients ?? [] as $client)
        {
            SendMessageHelper::SendMessage($client->phone,$data['message_body']);
        }
        
        return MarketingMessage::create($data);
    }

    public function getMarketingMessageById($id)
    {
        return MarketingMessage::find($id);
    }

    public function updateMarketingMessage($id, array $data)
    {
        $marketingMessage = MarketingMessage::find($id);
        if ($marketingMessage) {
            $data['client_ids'] = is_array($data['client_ids']) ? json_encode($data['client_ids']) : $data['client_ids'];
            $data['attachment_path'] = isset($data['attachment_path']) ? HandleUpload::uploadFile($data['attachment_path'],'attachments') : $marketingMessage->attachment_path;
            $marketingMessage->update($data);
            return $marketingMessage;
        }
        return null;
    }

    public function deleteMarketingMessage($id)
    {
        $marketingMessage = MarketingMessage::find($id);
        return $marketingMessage ? $marketingMessage->delete() : false;
    }
}