<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\SendMessageHelper;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SendMessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'person_name' => 'required',
            'mobile_number' => 'required'
        ]);

        $link = Setting::first()?->menu_image ?? NULL;

        $menu = Storage::disk('public')->url($link);

        $message = "Welcome " . $request->person_name . ', this is the link to our menu ' . $menu;
        
        $response = SendMessageHelper::SendMessage($request->mobile_number,$message);
        
        if ($response->successful()) {
            return response()->json(['message' => 'Message sent successfully']);
        } else {
            return response()->json(['error' => 'Failed to send message'], 500);
        }
    }
}