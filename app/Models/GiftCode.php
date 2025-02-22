<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GiftCode extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function gifts()
    {
        return $this->hasMany(Gift::class);
    }
}