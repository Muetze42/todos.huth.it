<?php

namespace App\Contracts\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait HasVisibilityTrait
{
    /**
     * Scope a query to only include popular users.
     */
    public function scopeAuthorized(Builder $query, ?User $user): void
    {
        if (!$user) {
            $query->where('visibility', VisibilityEnum::GUEST->value);
        } elseif (!$user->is_admin) {
            $query->where('visibility', '<', VisibilityEnum::ADMIN->value);
        }
    }
}
