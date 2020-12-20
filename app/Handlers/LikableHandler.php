<?php

namespace App\Handlers;

use App\Models\Like;

class LikableHandler
{
    public function toggle(
        $userId,
        $likableId,
        $likableType,
        $score = 1
    ): int {
        $entity = Like::withTrashed()
            ->where('likable_type', $likableType)
            ->where('likable_id', $likableId)
            ->where('user_id', $userId)
            ->first();

        if (is_null($entity)) {
            $entity = Like::create([
                'user_id' => $userId,
                'likable_id' => $likableId,
                'likable_type' => $likableType,
                'score' => $score
            ]);
        } else {
            if (is_null($entity->deleted_at)) {
                $entity->delete();

                return -1 * $entity->score;
            } else {
                $entity->restore();
            }
        }

        return $entity->score;
    }
}
