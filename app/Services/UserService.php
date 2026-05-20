<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function create(array $data)
    {
        return User::query()->create($data);
    }

    public function findById(int $id)
    {
        return User::query()->find($id);
    }

    public function update($id, array $data)
    {
        $user = User::findOrFail($id);
        // Filter null values so they don't overwrite existing data
        $filteredData = array_filter($data, fn($value) => !is_null($value));
        $user->update($filteredData);
        return $user;
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }

    public function getAll()
    {
        return User::query()->get();
    }
}
