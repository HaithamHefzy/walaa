<?php

namespace App\Repositories;

use App\Models\MarketingMessage;
use App\Helpers\HandleUpload;
use App\Models\Client;
use App\Helpers\SendMessageHelper;
use Illuminate\Support\Facades\Storage;

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

        $ids = is_array($ids) ? $ids : [$ids];
        $clients = $data['send_to'] == 'all' ? Client::all() : Client::whereIn('id',$ids)->get();

        foreach($clients ?? [] as $client)
        {
            $link = isset($data['attachment_path']) ? Storage::disk('public')->url($data['attachment_path']) : NULL;

            $message =  $data['message_body'] . " " . $link;

            $response = SendMessageHelper::SendMessage($client->phone,$message);
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
        
        if ($marketingMessage) 
        {
            $ids = is_array($data['client_ids']) ? $data['client_ids'] : json_decode($data['client_ids']);
            $data['client_ids'] = is_array($data['client_ids']) ? json_encode($data['client_ids']) : $data['client_ids'];
            $data['delivery_method'] = is_array($data['delivery_method']) ? json_encode($data['delivery_method']) : $data['delivery_method'];
            $data['attachment_path'] = isset($data['attachment_path']) ? HandleUpload::uploadFile($data['attachment_path'],'attachments') : NULL;

            $ids = is_array($ids) ? $ids : [$ids];
            $clients = $data['send_to'] == 'all' ? Client::all() : Client::whereIn('id',$ids)->get();

            foreach($clients ?? [] as $client)
            {
                $link = isset($data['attachment_path']) ? Storage::disk('public')->url($data['attachment_path']) : NULL;

                $message =  $data['message_body'] . " " . $link;

                $response = SendMessageHelper::SendMessage($client->phone,$message);
            }
            
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