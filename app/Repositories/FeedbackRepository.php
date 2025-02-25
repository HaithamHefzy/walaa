<?php

namespace App\Repositories;

use App\Models\Feedback;
use Carbon\Carbon;

/**
 * FeedbackRepository
 * Handles direct database operations for the Feedback model.
 */
class FeedbackRepository
{
    /**
     * Retrieve feedback with optional pagination (no filters).
     */
    public function all($perPage)
    {
        $query = Feedback::latest();

        if (is_null($perPage)) {
            return $query->get();
        }

        return $query->paginate($perPage);
    }

    /**
     * Filter feedback by date range, rating, etc.
     *
     * @param string|null $startDate
     * @param string|null $endDate
     * @param string|null $rating
     * @param int|null    $perPage
     * @return mixed
     */
    public function filter($startDate, $endDate, $rating, $perPage)
    {
        $query = Feedback::latest();

        // Date range filter
        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end   = Carbon::parse($endDate)->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        }

        // Rating filter
        if ($rating) {
            $query->where('rating', $rating);
        }

        // If you have other filters, add them here

        // Pagination
        if ($perPage) {
            return $query->paginate($perPage);
        }
        return $query->get();
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
