<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Http\Requests\User\{StoreUserRequest, UpdateUserRequest};
use App\Http\Resources\UserResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    use ApiResponse;

    protected UserService $userService;

    /**
     * Inject the UserService into the controller.
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Retrieve a paginated list of users.
     * Requires 'view users' permission.
     */
    public function index(): JsonResponse
    {

        $users = $this->userService->getAllUsers(request()->get('per_page'));
        return $this->successResponse(UserResource::collection($users), 'Users retrieved successfully');
    }

    /**
     * Store a new user.
     * Requires 'create users' permission.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {

        $user = $this->userService->createUser($request->validated());

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties([
                'new_user_id' => $user->id
            ])
            ->log('إضافة مستخدم جديد');

        return $this->successResponse(new UserResource($user), 'User created successfully', 201);
    }

    /**
     * Retrieve a specific user by ID.
     * Requires 'view users' permission.
     */
    public function show($id): JsonResponse
    {

        $user = $this->userService->getUserById($id);
        return $user
            ? $this->successResponse(new UserResource($user), 'User retrieved successfully')
            : $this->errorResponse('User not found', 404);
    }

    /**
     * Update an existing user.
     * Requires 'edit users' permission.
     */
    public function update(UpdateUserRequest $request, $id): JsonResponse
    {

        $user = $this->userService->updateUser($id, $request->validated());
        return $user
            ? $this->successResponse(new UserResource($user), 'User updated successfully')
            : $this->errorResponse('User not found', 404);
    }

    /**
     * Delete a user by ID.
     * Requires 'delete users' permission.
     */
    public function destroy($id): JsonResponse
    {

        return $this->userService->deleteUser($id)
            ? $this->successResponse([], 'User deleted successfully', 200)
            : $this->errorResponse('User not found', 404);
    }
}
