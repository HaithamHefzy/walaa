<?php

namespace App\Repositories;

use App\Models\FeedbackManager;

class FeedbackManagerRepository
{
    /**
     * Retrieve all feedback managers
     */
    public function all($perPage = null)
    {
        $query = FeedbackManager::latest();

        if (is_null($perPage)) {
            // If no per_page is specified, return all
            return $query->get();
        }

        return $query->paginate($perPage);
    }

    /**
     * Create a new feedback manager record.
     */
    public function create(array $data)
    {
        return FeedbackManager::create($data);
    }

    /**
     * Delete a feedback manager by its ID.
     */
    public function delete($id)
    {
        $manager = FeedbackManager::find($id);
        return $manager ? $manager->delete() : false;
    }
}
