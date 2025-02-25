<?php

namespace App\Http\Controllers;

use App\Services\VisitService;
use App\Http\Requests\StoreVisitRequest;
use App\Http\Resources\VisitResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * VisitController
 * Handles HTTP requests for visits, including creating a visit,
 * assigning a table, calling by button (A,B,C), and special call.
 */
class VisitController extends Controller
{
    use ApiResponse;

    protected VisitService $visitService;

    /**
     * Inject the VisitService into the controller.
     *
     * @param VisitService $visitService
     */
    public function __construct(VisitService $visitService)
    {
        $this->visitService = $visitService;
    }

    /**
     * GET /visits
     * Retrieve all visits with optional pagination (?per_page=XX).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page');
        $visits = $this->visitService->getAllVisits($perPage);

        return $this->successResponse(
            VisitResource::collection($visits),
            'Visits retrieved successfully'
        );
    }

    /**
     * POST /visits
     * Create a new visit (direct or waiting).
     * If table_id is provided, the system checks capacity and availability.
     *
     * @param StoreVisitRequest $request
     * @return JsonResponse
     */
    public function store(StoreVisitRequest $request): JsonResponse
    {
        $visit = $this->visitService->createVisit($request->validated());

        if ($visit instanceof \App\Models\Visit) {
            return $this->successResponse(
                new VisitResource($visit),
                'Visit created successfully',
                201
            );
        }

        if (is_array($visit) && isset($visit['message'])) {
            return $this->errorResponse($visit['message'], 400);
        }

        return $this->errorResponse('Could not create visit', 400);
    }

    /**
     * DELETE /visits/{id}
     * Delete a visit by ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $deleted = $this->visitService->deleteVisit($id);

        if ($deleted) {
            return $this->successResponse(null, 'Visit deleted successfully', 200);
        }
        return $this->errorResponse('Visit not found', 404);
    }

    /**
     * POST /visits/{visitId}/assign-table
     * Assign a table to the visit if possible.
     *
     * @param int $visitId
     * @param Request $request
     * @return JsonResponse
     */
    public function assignTable($visitId, Request $request): JsonResponse
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id'
        ]);

        $result = $this->visitService->assignTable($visitId, $request->table_id);

        if ($result === 'visit_not_found') {
            return $this->errorResponse('Visit not found', 404);
        }
        if ($result === 'table_not_found') {
            return $this->errorResponse('Table not found', 404);
        }
        if ($result === 'table_already_taken') {
            return $this->errorResponse('Table is unavailable', 400);
        }
        if ($result === 'over_capacity') {
            return $this->errorResponse('Too many people for this table', 400);
        }

        return $this->successResponse(null, 'Table assigned successfully');
    }

    /**
     * POST /visits/call-button/{buttonType}
     * Call the earliest waiting visit for the given button type (A, B, C),
     * optionally with a table assignment.
     * If table_id is provided in the request body, it will be used;
     * otherwise, the visit will be called without table assignment.
     *
     * Example: POST {{base_url}}/api/visits/call-button/A
     * Body (raw JSON): { "table_id": 5 }  // optional
     *
     * @param string $buttonType
     * @param Request $request
     * @return JsonResponse
     */
    public function callButton($buttonType, Request $request): JsonResponse
    {
        $tableId = $request->input('table_id');
        $result = $this->visitService->callByButton($buttonType, $tableId);

        if ($result['status'] === 'error') {
            return $this->errorResponse($result['message'], 400);
        }

        return $this->successResponse(null, $result['message'], 200);
    }

    /**
     * POST /visits/special-call/{visitId}
     * Special call for any waiting visit by ID, bypassing the queue.
     * Table assignment is optional.
     *
     * Example: POST {{base_url}}/api/visits/special-call/10
     * Body (raw JSON): { "table_id": 3 }  // optional
     *
     * @param int $visitId
     * @param Request $request
     * @return JsonResponse
     */
    public function specialCall($visitId, Request $request): JsonResponse
    {
        $tableId = $request->input('table_id');
        $result = $this->visitService->specialCall($visitId, $tableId);

        if ($result['status'] === 'error') {
            return $this->errorResponse($result['message'], 400);
        }

        return $this->successResponse(null, $result['message'], 200);
    }

    /**
     * GET /visits/waiting
     * Retrieve all visits that are currently in waiting status,
     * including time since creation and dynamic classification.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getWaitingVisits(Request $request): JsonResponse
    {
        $visits = \App\Models\Visit::where('status', 'waiting')
            ->orderBy('created_at', 'asc')
            ->get();

        return $this->successResponse(
            \App\Http\Resources\VisitResource::collection($visits),
            'Waiting visits retrieved successfully'
        );
    }

    /**
     * GET /visits/last-called
     * Retrieve the most recently called visit (the last one with status = 'called').
     *
     * @return JsonResponse
     */
    public function lastCalled(): JsonResponse
    {
        // Find the most recent visit with status 'called'
        $visit = \App\Models\Visit::where('status', 'called')
            ->orderBy('updated_at', 'desc')
            ->first();

        if (!$visit) {
            return $this->errorResponse('No called visit found', 404);
        }

        // Return the visit data using VisitResource
        return $this->successResponse(
            new \App\Http\Resources\VisitResource($visit),
            'Last called visit retrieved successfully'
        );
    }

    /**
     * GET /visits/stats
     * Retrieve various statistics for the dashboard, such as:
     * - Number of waiting visits
     * - Number of called visits
     * - Number of available tables
     * - Number of occupied tables
     */
    public function stats(): JsonResponse
    {
        // Example queries:
        $waitingVisitsCount = \App\Models\Visit::where('status', 'waiting')->count();
        $calledVisitsCount  = \App\Models\Visit::where('status', 'called')->count();

        // If you have a Table model to check availability
        $availableTablesCount = \App\Models\Table::where('status', 'available')->count();
        $unavailableTablesCount = \App\Models\Table::where('status', 'unavailable')->count();

        // Build the response data array
        $data = [
            'waiting_visits'       => $waitingVisitsCount,
            'called_visits'        => $calledVisitsCount,
            'available_tables'     => $availableTablesCount,
            'unavailable_tables'   => $unavailableTablesCount,
        ];

        return $this->successResponse($data, 'Statistics retrieved successfully');
    }

    /**
     * GET /best-client
     * Retrieve the best client by number of visits.
     *
     * @return JsonResponse
     */
    public function bestClient(): JsonResponse
    {
        // Use withCount to get the count of visits for each client.
        $bestClient = \App\Models\Client::withCount('visits')
            ->orderBy('visits_count', 'desc')
            ->first();

        if (!$bestClient) {
            return $this->errorResponse('No clients found', 404);
        }

        $data = [
            'name'         => $bestClient->name,
            'total_visits' => $bestClient->visits_count,
        ];

        return $this->successResponse($data, 'Best client retrieved successfully');
    }

}
