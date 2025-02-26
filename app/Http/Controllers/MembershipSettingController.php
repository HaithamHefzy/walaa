<?php

namespace App\Http\Controllers;

use App\Services\MembershipSettingService;
use App\Http\Resources\MembershipSettingResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * MembershipSettingController
 * Manages membership settings by retrieving and updating the settings.
 */
class MembershipSettingController extends Controller
{
    use ApiResponse;

    protected MembershipSettingService $membershipService;

    /**
     * Inject the MembershipSettingService into the controller.
     *
     * @param MembershipSettingService $membershipService
     */
    public function __construct(MembershipSettingService $membershipService)
    {
        $this->membershipService = $membershipService;
    }

    /**
     * GET /membership-settings
     * Retrieve all membership settings with optional pagination.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page');
        $settings = $this->membershipService->getAllMembershipSettings($perPage);

        return $this->successResponse(
            MembershipSettingResource::collection($settings),
            'Membership settings retrieved successfully'
        );
    }

    /**
     * GET /membership-settings/current
     * Retrieve the current (latest) membership setting record.
     *
     * @return JsonResponse
     */
    public function current(): JsonResponse
    {
        $setting = $this->membershipService->getCurrentSettings();
        if (!$setting) {
            return $this->errorResponse('No membership settings found', 404);
        }
        return $this->successResponse(new MembershipSettingResource($setting), 'Current membership setting retrieved successfully');
    }

    /**
     * POST /membership-settings/update-multiple
     * Update membership settings in one request.
     *
     * Expected JSON Body:
     * {
     *     "platinum_visits": 200,
     *     "gold_visits": 100,
     *     "silver_visits": 50
     * }
     *
     * After updating, returns the updated membership settings.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateMultiple(Request $request): JsonResponse
    {
        // Validate the input data
        $validated = $request->validate([
            'platinum_visits' => 'required|integer|min:1',
            'gold_visits'     => 'required|integer|min:1',
            'silver_visits'   => 'required|integer|min:1',
        ]);

        // Update the membership settings using the service
        $this->membershipService->updateSettings($validated);

        activity()
            ->causedBy(auth()->user())
            ->withProperties($validated)
            ->log('إعدادات النظام تم تحديثها');

        // Retrieve the updated membership setting record
        $updatedSetting = $this->membershipService->getCurrentSettings();

        return $this->successResponse(
            new MembershipSettingResource($updatedSetting),
            'Membership settings updated successfully',
            200
        );
    }
}
