<?php

namespace App\Http\Controllers;

use App\Services\MembershipSettingService;
use App\Http\Requests\StoreMembershipSettingRequest;
use App\Http\Resources\MembershipSettingResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * MembershipSettingController
 * Manages CRUD for membership settings.
 */
class MembershipSettingController extends Controller
{
    use ApiResponse;

    protected MembershipSettingService $membershipService;

    public function __construct(MembershipSettingService $membershipService)
    {
        $this->membershipService = $membershipService;
    }

    /**
     * Retrieve all settings with optional pagination (?per_page=XX).
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
     * Create a new membership setting record.
     */
    public function store(StoreMembershipSettingRequest $request): JsonResponse
    {
        $setting = $this->membershipService->createMembershipSetting($request->validated());
        return $this->successResponse(
            new MembershipSettingResource($setting),
            'Membership setting created successfully',
            201
        );
    }

    /**
     * Delete a membership setting record.
     */
    public function destroy($id): JsonResponse
    {
        $deleted = $this->membershipService->deleteMembershipSetting($id);
        if ($deleted) {
            return $this->successResponse(null, 'Membership setting deleted successfully', 200);
        }
        return $this->errorResponse('Membership setting not found', 404);
    }

    /**
     * Retrieve the current (latest) membership setting record.
     */
    public function current(): JsonResponse
    {
        $setting = $this->membershipService->getCurrentSettings();
        if (!$setting) {
            return $this->errorResponse('No membership settings found', 404);
        }
        return $this->successResponse(new MembershipSettingResource($setting), 'Current membership setting');
    }
}
