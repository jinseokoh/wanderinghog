<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'provider' => 'required|string',
            'provider_id' => 'required|string',
        ]);

        $user = $request->user();
        $provider = $request->input('provider');
        $providerId = $request->input('provider_id');
        $user->socialProviders()->create([
            'provider' => $provider,
            'provider_id' => $providerId,
        ]);

        return response()->json([
            'data' => 'ok',
        ]);
    }
}
