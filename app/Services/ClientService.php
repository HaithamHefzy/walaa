<?php

namespace App\Services;

use App\Repositories\ClientRepository;
use App\Repositories\MembershipSettingRepository;

/**
 * Encapsulates business logic for clients.
 */
class ClientService
{
    protected $clientRepo;
    protected $membershipRepo;

    /**
     * Inject the ClientRepository into the service.
     */
    public function __construct(
        ClientRepository $clientRepo,
        MembershipSettingRepository $membershipRepo
    ) {
        $this->clientRepo = $clientRepo;
        $this->membershipRepo = $membershipRepo;
    }

    /**
     * Retrieve all clients with optional pagination.
     */
    public function getAllClients($perPage = null)
    {
        return $this->clientRepo->all($perPage);
    }

    /**
     * Delete a client by ID.
     */
    public function deleteClient($id)
    {
        return $this->clientRepo->delete($id);
    }

    /**
     * Find a client by ID.
     */
    public function find($id)
    {
        return $this->clientRepo->find($id);
    }

    /**
     * Calculate membership based on visit counts and membership settings.
     */
    public function getMembershipType($clientId): string
    {
        $count = $this->clientRepo->countVisits($clientId);
        $settings = $this->membershipRepo->currentSettings(); // e.g. fetch from membership_settings

        if (!$settings) {
            return 'normal'; // if no membership settings exist
        }

        // Compare count with membership thresholds
        if ($count >= $settings->platinum_visits) {
            return 'platinum';
        } elseif ($count >= $settings->gold_visits) {
            return 'gold';
        } elseif ($count >= $settings->silver_visits) {
            return 'silver';
        }
        return 'normal';
    }

    /**
     * Return the last visit date for a client, or null if none exist.
     */
    public function getLastVisitDate($clientId)
    {
        return $this->clientRepo->lastVisitDate($clientId);
    }

    /**
     * Retrieve a full profile of the client, including membership and visits.
     */
    public function getClientProfile($id)
    {
        $client = $this->clientRepo->find($id);


        if (!$client) {
            return null;
        }

        // membership
        $membershipType = $this->getMembershipType($id);

        // visits
        $visits = $this->clientRepo->getVisitsByClient($id);

        // last visit date
        $lastVisitDate = $this->clientRepo->lastVisitDate($id);

        // total visits
        $totalVisits = $this->clientRepo->countVisits($id);

        return [
            'client_info' => [
                'id'              => $client->id,
                'name'            => $client->name,
                'phone'           => $client->phone,
                'membership_type' => $membershipType,
                'created_at'      => $client->created_at,
                'total_visits'    => $totalVisits,
                'last_visit_date' => $lastVisitDate,
            ],
            'visits' => $visits, // or you could transform them via VisitResource
        ];
    }

}
