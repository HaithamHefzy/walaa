<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class SendMessageHelper
{
    public static function SendMessage($mobile,$message)
    {
        $tenantId = '114046';
        $accessToken = env('MOBILE_TOKEN');
        $apiUrl = "https://live-mt-server.wati.io/{$tenantId}/api/v1/sendTemplateMessage?whatsappNumber=$mobile";

        $payload = [
            "template_name"  => "walaa4",
            "broadcast_name" => "string",
            "parameters"   => [["name"  => "name","value" => $message]]
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('MOBILE_TOKEN'),
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ])->post($apiUrl, $payload);
        
        return $response;
    }
}