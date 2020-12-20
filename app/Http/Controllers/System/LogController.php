<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Log;

class LogController extends Controller
{
    public function index(string $id)
    {
        $log = Log::where('id', $id)->first();

        if (! $log) {
            return response()->json([
                'provider' => null,
                'provider_id' => null,
                'title' => '해당 요청은 발견되지 않습니다.',
                'description' => '삭제요청시 미소(MeSo)에서 발급해드린 아이디가 맞는지 다시한번 확인해주세요.',
                'read_at' => null,
                'created_at' => null,
            ]);
        }

        return response()->json([
            'provider' => $log->provider,
            'provider_id' => $log->provider_id,
            'title' => $log->title,
            'description' => $log->description,
            'read_at' => $log->read() ? $log->read_at->diffForHumans() : null,
            'created_at' => $log->created_at->diffForHumans(),
        ]);
    }

    public function update(string $id)
    {
        $log = Log::where('id', $id)->first();
        if (! $log) {
            return response()->json([
                'message' => 'Log not found.',
            ], 422);
        }

        $log->markAsRead();

        return response()->json([
            'message' => 'Marked it as read.'
        ]);

    }
}
