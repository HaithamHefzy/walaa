<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\SendMessageHelper;

class SendMessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required',
            'message' => 'required'
        ]);
        
        $response = SendMessageHelper::SendMessage($request->mobile_number,$request->message);
        
        if ($response->successful()) {
            return response()->json(['message' => 'Message sent successfully']);
        } else {
            return response()->json(['error' => 'Failed to send message'], 500);
        }
    }
}