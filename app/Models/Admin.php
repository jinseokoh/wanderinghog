<?php

namespace App\Models;

use App\Notifications\ResetAdminPassword;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable implements CanResetPassword
{
    use Notifiable;

    protected $guard = 'admin';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'bool'
    ];

    // ================================================================================
    // CanResetPassword interfaces
    // ================================================================================

    /**
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetAdminPassword($token));
    }

    // ================================================================================
    // helpers
    // ================================================================================

    /**
     * Get a string path for the thread.
     *
     * @return string
     */
    public function path()
    {
        return "/admins/{$this->id}";
    }
}
