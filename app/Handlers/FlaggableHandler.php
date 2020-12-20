<?php

namespace App\Handlers;

use App\Models\Flag;

class FlaggableHandler
{
    public function toggle(
        $userId,
        $flaggableId,
        $flaggableType,
        $score = 1
    ): int {
        $entity = Flag::withTrashed()
            ->where('flaggable_type', $flaggableType)
            ->where('flaggable_id', $flaggableId)
            ->where('user_id', $userId)
            ->first();

        if (is_null($entity)) {
            $entity = Flag::create([
                'user_id' => $userId,
                'flaggable_id' => $flaggableId,
                'flaggable_type' => $flaggableType,
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
