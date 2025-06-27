<?php

namespace App\Policies;

use App\Models\Dataset;
use App\Models\User;

class DatasetPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view datasets');
    }

    public function view(?User $user, Dataset $dataset): bool
    {
        // Published datasets can be viewed by anyone
        if ($dataset->is_published) {
            return true;
        }

        // Non-published datasets require authentication and permission
        if (!$user) {
            return false;
        }

        return $user->canManageDataset($dataset) || $user->can('view datasets');
    }

    public function create(User $user): bool
    {
        return $user->can('create datasets');
    }

    public function update(User $user, Dataset $dataset): bool
    {
        return $user->canManageDataset($dataset) || $user->can('edit datasets');
    }

    public function delete(User $user, Dataset $dataset): bool
    {
        return $user->canManageDataset($dataset) || $user->can('delete datasets');
    }

    public function publish(User $user, Dataset $dataset): bool
    {
        return $user->canManageDataset($dataset) || $user->can('publish datasets');
    }

    public function approve(User $user, Dataset $dataset): bool
    {
        return $user->canApproveDataset($dataset) || $user->can('approve datasets');
    }

    public function feature(User $user, Dataset $dataset): bool
    {
        return $user->can('feature datasets');
    }
}