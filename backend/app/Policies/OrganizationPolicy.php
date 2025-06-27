<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;

class OrganizationPolicy
{
    public function viewAny(?User $user): bool
    {
        return true; // Organizations are public
    }

    public function view(?User $user, Organization $organization): bool
    {
        return true; // Organizations are public
    }

    public function create(User $user): bool
    {
        return $user->can('create organizations');
    }

    public function update(User $user, Organization $organization): bool
    {
        // Super admin can edit any organization
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Organization admin can edit their own organization
        if ($user->hasRole('organization-admin') && $user->organization_id === $organization->id) {
            return true;
        }

        return $user->can('edit organizations');
    }

    public function delete(User $user, Organization $organization): bool
    {
        return $user->can('delete organizations');
    }
}