<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Visit Model
 * Represents a single visit or waiting entry for a client.
 */
class Visit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'number_of_people',
        'source',
        'status',
        'table_id',
        'waiting_number',
    ];

    /**
     * Each visit belongs to a single client.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
