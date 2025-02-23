<?php

namespace App\Http\Controllers;

use App\Services\VisitService;
use App\Http\Requests\StoreVisitRequest;
use App\Http\Resources\VisitResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    use ApiResponse;

    protected VisitService $visitService;

    public function __construct(VisitService $visitService)
    {
        $this->visitService = $visitService;
    }

    /**
     * GET /visits
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
     */
    public function store(StoreVisitRequest $request): JsonResponse
    {
        $visit = $this->visitService->createVisit($request->validated());

        return $this->successResponse(
            new VisitResource($visit),
            'Visit created successfully',
            201
        );
    }

    /**
     * DELETE /visits/{id}
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
     * Assign a table to the visit if possible.
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
}
