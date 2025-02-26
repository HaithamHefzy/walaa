<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientVisitRequest;
use App\Http\Resources\VisitResource;
use App\Models\Client;
use App\Models\Visit;
use App\Services\ClientService;
use App\Http\Requests\StoreClientRequest;
use App\Http\Resources\ClientResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Handles HTTP requests for clients.
 */
class ClientController extends Controller
{
    use ApiResponse;

    protected ClientService $clientService;

    /**
     * Inject the ClientService into the controller.
     */
    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    /**
     * GET /clients
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page');
        $clients = $this->clientService->getAllClients($perPage);

        return $this->successResponse(
            ClientResource::collection($clients),
            'Clients retrieved successfully'
        );
    }

    /**
     * Create or retrieve a client and create a visit record.
     *
     * @param StoreClientVisitRequest $request
     * @return JsonResponse
     */
    public function store(StoreClientVisitRequest $request): JsonResponse
    {
        // Retrieve validated data from the request
        $validated = $request->validated();

        // Check if a client exists based on phone number
        $client = Client::where('phone', $validated['phone'])->first();

        // If client does not exist, create a new client
        if (!$client) {
            $client = Client::create([
                'name'  => $validated['name'],
                'phone' => $validated['phone']
            ]);
        }

        // Prepare visit data linked to the client
        $visitData = [
            'client_id'        => $client->id,
            'number_of_people' => $validated['number_of_people'] ?? null,
            'source'           => $validated['source'],
            'status'           => $validated['status'],
            'table_id'         => $validated['table_id'] ?? null,
        ];

        // Create the visit record
        $visit = Visit::create($visitData);

        // Calculate the waiting_number for today
        $today = now()->format('Y-m-d');
        $maxToday = Visit::whereDate('created_at', $today)->max('waiting_number');
        $nextNumber = $maxToday ? $maxToday + 1 : 1;

        // Save the waiting_number in the visit and update the record
        $visit->waiting_number = $nextNumber;
        $visit->save();

        // Log the creation event with Spatie Activity Log,
        // including the waiting number and client name in Arabic.
        activity()
            ->causedBy(auth()->user())
            ->performedOn($visit)
            ->withProperties([
                'client_id'      => $client->id,
                'visit_id'       => $visit->id,
                'waiting_number' => $nextNumber,
            ])
            ->log('تم استدعاء العميل: ' . $client->name . ' عبر النظام');

        // Return combined response with client and visit data using resources
        return $this->successResponse(
            [
                'client' => new ClientResource($client),
                'visit'  => new VisitResource($visit)
            ],
            'Client and visit created successfully',
            201
        );
    }

    /**
     * GET /clients/{id}
     * Retrieve a specific client by ID.
     */
    public function show($id): JsonResponse
    {
        $client = $this->clientService->find($id);

        if (!$client) {
            return $this->errorResponse('Client not found', 404);
        }

        return $this->successResponse(
            new ClientResource($client),
            'Client retrieved successfully'
        );
    }

    /**
     * DELETE /clients/{id}
     */
    public function destroy($id): JsonResponse
    {
        $deleted = $this->clientService->deleteClient($id);

        if ($deleted) {
            activity()
                ->causedBy(auth()->user())
                ->withProperties(['client_id' => $id])
                ->log('Client was deleted');
            return $this->successResponse(null, 'Client deleted successfully', 200);
        }
        return $this->errorResponse('Client not found', 404);
    }

    /**
     * GET /clients/{id}/membership
     * Returns membership type (platinum, gold, silver, normal).
     */
    public function membership($id): JsonResponse
    {
        $type = $this->clientService->getMembershipType($id);
        return $this->successResponse(
            ['membership_type' => $type],
            'Membership type calculated'
        );
    }

    /**
     * GET /clients/{id}/last-visit
     * Returns the last visit date.
     */
    public function lastVisit($id): JsonResponse
    {
        $date = $this->clientService->getLastVisitDate($id);
        if (!$date) {
            return $this->errorResponse('No visits found for this client', 404);
        }
        return $this->successResponse(['last_visit' => $date], 'Last visit date retrieved');
    }

    /**
     * GET /clients/{id}/profile
     * Retrieve a full profile of the client, including membership and visits.
     */
    public function profile($id): JsonResponse
    {
        $profileData = $this->clientService->getClientProfile($id);

        if (!$profileData) {
            return $this->errorResponse('Client not found', 404);
        }

        return $this->successResponse($profileData, 'Client profile retrieved successfully');
    }

}
