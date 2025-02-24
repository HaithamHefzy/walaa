<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedbackManager extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'feedback_manager';
    protected $fillable = [
        'name',
        'email',
        'phone_number',
    ];

    /**
     * Relationship to Feedback
     */
    public function feedbacks()
    {
        return $this->belongsToMany(Feedback::class, 'feedback_feedback_manager');
    }
}
