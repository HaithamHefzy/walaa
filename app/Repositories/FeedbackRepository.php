<?php

namespace App\Repositories;

use App\Models\Feedback;

class FeedbackRepository
{
    /**
     * Retrieve all feedbacks
     */
    public function all($perPage = null)
    {
        $query = Feedback::latest();

        if (is_null($perPage)) {
            // If no per_page provided, just get all
            return $query->get();
        }

        return $query->paginate($perPage);
    }

    /**
     * Create a new feedback record.
     */
    public function create(array $data)
    {
        return Feedback::create($data);
    }

    /**
     * Delete a feedback by its ID.
     */
    public function delete($id)
    {
        $feedback = Feedback::find($id);
        return $feedback ? $feedback->delete() : false;
    }
}
