<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\MarketingCalendarService;
use App\Http\Requests\Marketing\{StoreMarketingCalendarRequest,UpdateMarketingCalendarRequest};
use App\Http\Resources\MarketingCalendarResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class CalendarController extends Controller
{
    use ApiResponse;

    protected MarketingCalendarService $marketingCalendarService;

    /**
     * Inject the MarketingCalendarService into the controller.
     */
    public function __construct(MarketingCalendarService $marketingCalendarService)
    {
        $this->marketingCalendarService = $marketingCalendarService;
    }

    /**
     * Retrieve a paginated list of marketing calendars.
     */
    public function index(): JsonResponse
    {
        $marketingCalendars = $this->marketingCalendarService->getAllMarketingCalendars(request()->get('per_page'));
        return $this->successResponse(MarketingCalendarResource::collection($marketingCalendars), 'Marketing calendars retrieved successfully');
    }

    /**
     * Retrieve a paginated list of coming marketing calendars.
     */
    public function coming(): JsonResponse
    {
        $marketingCalendars = $this->marketingCalendarService->getComingMarketingCalendars(request()->get('per_page'));
        return $this->successResponse(MarketingCalendarResource::collection($marketingCalendars), 'Coming marketing calendars retrieved successfully');
    }

    /**
     * Retrieve a paginated list of finished marketing calendars.
     */
    public function finished(): JsonResponse
    {
        $marketingCalendars = $this->marketingCalendarService->getFinishedMarketingCalendars(request()->get('per_page'));
        return $this->successResponse(MarketingCalendarResource::collection($marketingCalendars), 'Finished marketing calendars retrieved successfully');
    }

    /**
     * Store a new marketing calendar.
     */
    public function store(StoreMarketingCalendarRequest $request): JsonResponse
    {
        $marketingCalendar = $this->marketingCalendarService->createMarketingCalendar($request->validated());

        return $this->successResponse(new MarketingCalendarResource($marketingCalendar), 'Marketing calendar created successfully', 201);
    }

    /**
     * Retrieve a specific marketing calendar by ID.
     */
    public function show($id): JsonResponse
    {
        $marketingCalendar = $this->marketingCalendarService->getMarketingCalendarById($id);
        return $marketingCalendar
            ? $this->successResponse(new MarketingCalendarResource($marketingCalendar), 'Marketing calendar retrieved successfully')
            : $this->errorResponse('Marketing calendar not found', 404);
    }

    /**
     * Update an existing marketing calendar.
     */
    public function update(UpdateMarketingCalendarRequest $request, $id): JsonResponse
    {
        $marketingCalendar = $this->marketingCalendarService->updateMarketingCalendar($id, $request->validated());
        return $marketingCalendar
            ? $this->successResponse(new MarketingCalendarResource($marketingCalendar), 'Marketing calendar updated successfully')
            : $this->errorResponse('Marketing calendar not found', 404);
    }

    /**
     * Delete a marketing calendar by ID.
     */
    public function destroy($id): JsonResponse
    {
        return $this->marketingCalendarService->deleteMarketingCalendar($id)
            ? $this->successResponse([], 'Marketing calendar deleted successfully', 200)
            : $this->errorResponse('Marketing calendar not found', 404);
    }
}