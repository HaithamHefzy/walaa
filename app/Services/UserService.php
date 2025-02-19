<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    protected UserRepository $userRepository;

    /**
     * Inject the UserRepository into the service.
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Retrieve all users with pagination.
     */
    public function getAllUsers($perPage)
    {
        return $this->userRepository->getAllUsers($perPage);
    }

    /**
     * Retrieve a single user by ID.
     */
    public function getUserById($id)
    {
        return $this->userRepository->findUserById($id);
    }

    /**
     * Create a new user and assign specific permissions.
     */
    public function createUser($data)
    {
        $user = $this->userRepository->createUser($data);
        if (isset($data['permissions'])) {
            $user->syncPermissions($data['permissions']);
        }
        return $user;
    }

    /**
     * Update an existing user and modify permissions.
     */
    public function updateUser($id, $data)
    {
        $user = $this->userRepository->updateUser($id, $data);
        if ($user && isset($data['permissions'])) {
            $user->syncPermissions($data['permissions']);
        }
        return $user;
    }

    /**
     * Delete a user by ID.
     */
    public function deleteUser($id)
    {
        return $this->userRepository->deleteUser($id);
    }
}
