<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function viewAny(?User $user): bool
    {
        return true; // Categories are public
    }

    public function view(?User $user, Category $category): bool
    {
        return true; // Categories are public
    }

    public function create(User $user): bool
    {
        return $user->can('create categories');
    }

    public function update(User $user, Category $category): bool
    {
        return $user->can('edit categories');
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->can('delete categories');
    }
}