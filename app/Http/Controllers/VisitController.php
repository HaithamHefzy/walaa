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
 * Handles HTTP requests for visits,
 * including creating a visit, assigning a table,
 * calling by button (A,B,C), and special call.
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

        // If createVisit returns a Visit model, it's successful.
        // Otherwise, if it returns a JSON response or array, handle accordingly.
        if ($visit instanceof \App\Models\Visit) {
            return $this->successResponse(
                new VisitResource($visit),
                'Visit created successfully',
                201
            );
        }

        // If there's an error scenario, you might handle it here:
        if (is_array($visit) && isset($visit['message'])) {
            return $this->errorResponse($visit['message'], 400);
        }

        // Fallback
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

        // If assigned successfully
        return $this->successResponse(null, 'Table assigned successfully');
    }

    /**
     * POST /visits/call-button/{buttonType}
     * Calls the earliest waiting visit for the given button type (A,B,C),
     * requiring a table_id in the request body.
     *
     * Example: POST /visits/call-button/A
     * Body: { "table_id": 5 }
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
     * Special call for any waiting visit by ID, ignoring the queue.
     * Table assignment is optional in special call.
     *
     * @param int $visitId
     * @return JsonResponse
     */
    public function specialCall($visitId, Request $request): JsonResponse
    {
        // Retrieve optional table_id from the request body
        $tableId = $request->input('table_id');
        $result = $this->visitService->specialCall($visitId, $tableId);

        if ($result['status'] === 'error') {
            return $this->errorResponse($result['message'], 404);
        }

        return $this->successResponse(null, $result['message'], 200);
    }

    /**
     * GET /visits/waiting
     * Retrieve all visits that are currently in waiting status,
     * including time since creation and optional classification.
     */
    public function getWaitingVisits(Request $request): JsonResponse
    {
        // get the waiting list visiting
        $visits = \App\Models\Visit::where('status', 'waiting')
            ->orderBy('created_at', 'asc')
            ->get();

        return $this->successResponse(
            \App\Http\Resources\VisitResource::collection($visits),
            'Waiting visits retrieved successfully'
        );
    }

}
