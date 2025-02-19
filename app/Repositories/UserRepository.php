<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    /**
     * Retrieve all users with pagination.
     */
    public function getAllUsers($perPage)
    {
        return is_null($perPage) ? User::get() : User::paginate($perPage);
    }

    /**
     * Create a new user.
     */
    public function createUser(array $data)
    {
        return User::create($data);
    }

    /**
     * Retrieve a user by ID.
     */
    public function findUserById($id)
    {
        return User::with('permissions')->find($id);
    }

    /**
     * Update a user.
     */
    public function updateUser($id, array $data)
    {
        $user = User::find($id);
        if ($user) {
            $user->update($data);
            return $user;
        }
        return null;
    }

    /**
     * Delete a user by ID.
     */
    public function deleteUser($id)
    {
        $user = User::find($id);
        return $user ? $user->delete() : false;
    }
}
