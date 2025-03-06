<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\SendMessageHelper;

class SendMessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'person_name' => 'required',
            'mobile_number' => 'required'
        ]);

        $menu = asset('menu.jpg');

        $message = "Welcome " . $request->person_name . ', this is the link to our menu ' . $menu;
        
        $response = SendMessageHelper::SendMessage($request->mobile_number,$message);
        
        if ($response->successful()) {
            return response()->json(['message' => 'Message sent successfully']);
        } else {
            return response()->json(['error' => 'Failed to send message'], 500);
        }
    }
}