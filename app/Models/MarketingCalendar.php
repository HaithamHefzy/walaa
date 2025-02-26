<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketingCalendar extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'marketing_calendar';

    protected $guarded = ['id'];
}