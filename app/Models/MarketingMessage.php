<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketingMessage extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function clients()
    {
        $ids = json_encode($this->client_ids);
        return $ids ? Client::whereIn('id',$ids)->get() : '';
    }
}