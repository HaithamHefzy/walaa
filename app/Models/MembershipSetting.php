<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * MembershipSetting Model
 * - references membership_settings table
 * - e.g. platinum_visits, gold_visits, silver_visits
 */
class MembershipSetting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'platinum_visits',
        'gold_visits',
        'silver_visits',
    ];
}
