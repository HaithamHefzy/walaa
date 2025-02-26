<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketingMessageSetting extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'marketing_messages_settings';

    protected $guarded = ['id'];
}