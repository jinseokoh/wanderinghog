<?php

namespace App\Handlers;

use App\Models\User;

class OtpHandler
{
    public function generateEmailOtp(String $prefix, User $user): string
    {
        $key = $this->getCacheKey($prefix, $user->id);
        $otp = (string) rand(100000, 999999);
        \Log::info("[$key]:[$otp]");
        \Cache::put($key, $otp, 60 * 15); // valid for 15 minutes

        return $otp;
    }

    public function getCacheKey(String $prefix, int $id)
    {
        return "{$prefix}-otp.{$id}";
    }
}
