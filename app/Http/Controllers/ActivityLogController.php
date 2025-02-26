<?php

namespace App\Http\Controllers;

use App\Http\Resources\ActivityResource;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    use ApiResponse;

    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $logs = \Spatie\Activitylog\Models\Activity::with(['causer', 'subject.client'])
            ->latest()
            ->paginate($request->get('per_page', 15));

        return $this->successResponse(
            ActivityResource::collection($logs),
            'Activity Data retrieved successfully'
        );
    }
}

