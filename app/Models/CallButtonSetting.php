<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * CallButtonSetting Model
 * - references call_buttons_settings table
 * - e.g. button_type (A,B,C), max_people
 */
class CallButtonSetting extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'call_buttons_settings';

    protected $fillable = [
        'button_type',
        'max_people',
    ];
}
