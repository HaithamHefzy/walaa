<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class SendMessageHelper
{
    public static function SendMessage($mobile,$message)
    {
        $tenantId = '114046';
        $mobile = $mobile;
        $accessToken = env('MOBILE_TOKEN');
        $apiUrl = "https://live-mt-server.wati.io/{$tenantId}/api/v1/sendTemplateMessage/{$mobile}";

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer $accessToken"
        ])->post($apiUrl, [
            "template_name" => "test10",
            "broadcast_name" =>  "string",
            'message' => $message
        ]);

        return $response;
    }
}