<?php

namespace App\Policies;

use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the owner.
     *
     * @param  \App\Models\Admin  $user
     * @param  \App\Models\Admin  $admin
     * @return mixed
     */
    public function update(Admin $user, Admin $admin)
    {
        return $user->id === 1 ? true : $user->id === $admin->id;
    }
}
