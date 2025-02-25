<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\TableService;
use App\Http\Resources\TableResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * TableController
 * Manages table operations including listing, creating multiple tables,
 * deleting, and freeing a table.
 */
class TableController extends Controller
{
    use ApiResponse;

    protected TableService $tableService;

    /**
     * Inject the TableService into the controller.
     *
     * @param TableService $tableService
     */
    public function __construct(TableService $tableService)
    {
        $this->tableService = $tableService;
    }

    /**
     * GET /tables
     * Retrieve all tables with optional pagination (?per_page=XX).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page');
        $tables = $this->tableService->getAllTables($perPage);

        return $this->successResponse(
            TableResource::collection($tables),
            'Tables retrieved successfully'
        );
    }

    /**
     * POST /tables
     * Creates multiple tables in a single request.
     * Expects a JSON body with "tables" as an array of objects.
     *
     * Example Body:
     * {
     *     "tables": [
     *         { "room_number": 101, "table_capacity": 4, "table_number": 1, "status": "available" },
     *         { "room_number": 102, "table_capacity": 6, "table_number": 2 }
     *     ]
     * }
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // 1) Validate the incoming request
        $validated = $request->validate([
            'tables'                       => 'required|array',
            'tables.*.room_number'        => 'required|integer',
            'tables.*.table_capacity'     => 'required|integer',
            'tables.*.table_number'       => 'required|integer',
            'tables.*.status'             => 'nullable|in:available,unavailable',
        ]);

        // 2) Create the tables via the service
        $createdTables = $this->tableService->createMultiple($validated['tables']);

        // 3) Return the newly created tables
        return $this->successResponse(
            TableResource::collection($createdTables),
            'Tables created successfully',
            201
        );
    }


    /**
     * DELETE /tables/{id}
     * Delete a table by its ID.
     *
     * @param int $id
     * @return JsonResponse
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
     * POST /tables/{tableId}/free
     * Free the table (set status to 'available').
     *
     * @param int $tableId
     * @return JsonResponse
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
