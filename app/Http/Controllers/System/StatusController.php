<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Jobs\SendSlackNotification;

class StatusController extends Controller
{
    public function index()
    {
        return response()->json([
            'online' => true,
            'version' => config('app.version')
        ]);
    }

    public function test()
    {
        try {
            SendSlackNotification::dispatch();
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }

        return response()->json([
            'sent' => 'ok',
        ]);
    }
}
