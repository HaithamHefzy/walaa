<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
{
    protected $table ='feedbacks';
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_name',
        'phone_number',
        'food_quality',
        'service_quality',
        'value_for_money',
        'notes',
    ];

    /**
     * Relationship to FeedbackManager
     */
    public function feedbackManagers()
    {
        return $this->belongsToMany(FeedbackManager::class, 'feedback_feedback_manager');
    }
}
