<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTableRequest;
use App\Services\TableService;
use App\Http\Resources\TableResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Handles HTTP requests for tables.
 */
class TableController extends Controller
{
    use ApiResponse;

    protected TableService $tableService;

    public function __construct(TableService $tableService)
    {
        $this->tableService = $tableService;
    }

    /**
     * GET /tables
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page');
        $tables = $this->tableService->getAllTables($perPage);

        return $this->successResponse(TableResource::collection($tables), 'Tables retrieved successfully');
    }

    /**
     * POST /tables
     */
    public function store(StoreTableRequest $request): JsonResponse
    {
        $table = $this->tableService->createTable($request->validated());
        return $this->successResponse(new TableResource($table), 'Table created successfully', 201);
    }

    /**
     * DELETE /tables/{id}
     */
    public function destroy($id): JsonResponse
    {
        $deleted = $this->tableService->deleteTable($id);
        if ($deleted) {
            return $this->successResponse(null, 'Table deleted successfully', 200);
        }
        return $this->errorResponse('Table not found', 404);
    }

    /**
     * Free the table (set status=available).
     */
    public function free($tableId): JsonResponse
    {
        $result = $this->tableService->freeTable($tableId);
        if ($result === 'table_freed') {
            return $this->successResponse(null, 'Table is now available');
        }
        return $this->errorResponse('Table not found', 404);
    }
}
