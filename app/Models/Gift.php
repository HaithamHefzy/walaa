<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gift extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function giftCode()
    {
        return $this->belongsTo(GiftCode::class);
    }
}