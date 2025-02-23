<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Table Model
 * - references tables table
 * - status: available|unavailable
 */
class Table extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'room_number',
        'room_capacity',
        'table_number',
        'status',
    ];

    // If you need inverse relation, e.g. to find current visits referencing the table:
    public function visits()
    {
        return $this->hasMany(Visit::class, 'table_id');
    }
}
