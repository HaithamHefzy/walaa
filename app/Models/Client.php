<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Client Model
 * Represents a client with basic info like name, phone.
 */
class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
    ];

    /**
     * Relation to visits: one client has many visits.
     */
    public function visits()
    {
        return $this->hasMany(Visit::class, 'client_id');
    }
}
