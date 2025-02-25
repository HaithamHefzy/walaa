<?php

namespace App\Repositories;

use App\Models\Client;
use App\Models\Visit;

/**
 * Handles direct database operations for the Client model.
 */
class ClientRepository
{
    /**
     * Retrieve a list of clients.
     */
    public function all($perPage)
    {
        $query = Client::latest();
        return is_null($perPage) ? $query->get() : $query->paginate($perPage);
    }

    /**
     * Delete a client by ID.
     */
    public function delete($id)
    {
        $client = Client::find($id);
        return $client ? $client->delete() : false;
    }

    /**
     * Find a client by ID.
     */
    public function find($id)
    {
        return Client::find($id);
    }

    /**
     * Count total visits for membership logic.
     */
    public function countVisits($clientId)
    {
        return Client::find($clientId)?->visits()->count() ?? 0;
    }

    /**
     * Return the date of the last visit for a given client.
     */
    public function lastVisitDate($clientId)
    {
        $visit = Client::find($clientId)?->visits()->latest()->first();
        return $visit?->created_at;
    }

    /**
     * Retrieve all visits for a given client, ordered by created_at descending.
     */
    public function getVisitsByClient($clientId)
    {
        $client = Client::find($clientId);
        if (!$client) {
            return collect([]);
        }

        return $client->visits()->orderBy('created_at', 'desc')->get();
    }
}
