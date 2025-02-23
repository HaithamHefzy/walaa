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
    public function __construct(ClientRepository $clientRepo, MembershipSettingRepository $membershipRepo)
    {
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

//    /**
//     * Create a new client record.
//     */
//    public function createClient(array $data)
//    {
//        return $this->clientRepo->create($data);
//    }

    /**
     * Delete a client by ID.
     */
    public function deleteClient($id)
    {
        return $this->clientRepo->delete($id);
    }

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

    public function getLastVisitDate($clientId)
    {
        return $this->clientRepo->lastVisitDate($clientId);
    }
}
