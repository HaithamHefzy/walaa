<?php

namespace App\Repositories;

use App\Models\Gift;
use Carbon\Carbon;
use App\Helpers\SendMessageHelper;

class GiftRepository
{
    /**
     * Retrieve all gifts with pagination.
     */
    public function getAllGifts($perPage)
    {
        return is_null($perPage) ? Gift::get() : Gift::paginate($perPage);
    }

    /**
     * Create a new gift.
     */
    public function createGift(array $data)
    {
        $gift = Gift::create($data);

        SendMessageHelper::SendMessage($gift->friend_phone,'Your frient' . $gift->client_name . ' has sent you a gift code ' . $gift->giftCode?->code);

        return $gift;
    }

    /**
     * Retrieve a gift by ID.
     */
    public function getGiftById($id)
    {
        return Gift::find($id);
    }

    /**
     * Update a gift.
     */
    public function updateGift($id, array $data)
    {
        $gift = Gift::find($id);
        if ($gift) {
            $gift->update($data);
            return $gift;
        }
        return null;
    }

    /**
     * Update gift status.
    */
    public function useTheGift($data)
    {
        $gift = Gift::where('friend_phone',$data['friend_phone'])->whereHas('giftCode',function($code) use($data){
            $code->where('code',$data['code']);
        })->first();

        if ($gift) {
            if($gift->is_redeemed == 1){
                return 'The gift has already been redeemed before';
            }else{

                $giftCode = $gift->giftCode;
                $createdAt = Carbon::parse($giftCode->created_at);
                $now = Carbon::now();

                $canUseAfter = $createdAt->copy()->addHours($giftCode->validity_after_hours);
                $canUseNow = $now->greaterThanOrEqualTo($canUseAfter); 

                $expiresAt = $createdAt->copy()->addDays($giftCode->validity_days);
                $isStillValid = $now->lessThanOrEqualTo($expiresAt);

                if (!$canUseNow) {
                    return "The gift code is not yet available for use.";
                } elseif (!$isStillValid) {
                    return "The gift code has expired.";
                } else {
                    $gift->update(['is_redeemed' => 1]);
                    return 'The gift has been successfully redeemed';
                }
            }
        }
        return false;
    }

    /**
     * Delete a gift by ID.
     */
    public function deleteGift($id)
    {
        $gift = Gift::find($id);
        return $gift ? $gift->delete() : false;
    }
}