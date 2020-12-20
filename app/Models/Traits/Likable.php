<?php

namespace App\Models\Traits;

use App\Models\Like;
use App\Models\LikeCounter;

trait Likable {

    /**
     * Fetch only records that currently logged in user has liked
     */
    public function scopeWhereLiked($query, $userId = null)
    {
        if (is_null($userId)) {
            $userId = $this->loggedInUserId();
        }

        return $query->whereHas('likes', function($q) use($userId) {
            $q->where('user_id', '=', $userId);
        });
    }

    /**
     * Populate the $model->like_count attribute
     */
    public function getLikeCountAttribute()
    {
        return $this->likeCounter ? $this->likeCounter->count : 0;
    }

    /**
     * Collection of the flags on this record
     */
    public function likes()
    {
        return $this->morphMany('\App\Models\Like', 'likable');
    }

    /**
     * Counter is a record that stores the total flags for the
     * morphed record
     */
    public function likeCounter()
    {
        return $this->morphOne('\App\Models\LikeCounter', 'likable');
    }

    /**
     * Add a flag for this record by the given user.
     * @param $userId mixed - If null will use currently logged in user.
     */
    public function like($userId = null)
    {
        if (is_null($userId)) {
            $userId = $this->loggedInUserId();
        }

        if ($userId) {
            $like = $this->likes()
                ->where('user_id', '=', $userId)
                ->first();

            if ($like) return;

            $like = new Like();
            $like->user_id = $userId;
            $this->likes()->save($like);
        }

        $this->incrementLikeCount();
    }

    /**
     * Remove a flag from this record for the given user.
     * @param $userId mixed - If null will use currently logged in user.
     */
    public function unlike($userId = null)
    {
        if (is_null($userId)) {
            $userId = $this->loggedInUserId();
        }

        if ($userId) {
            $like = $this->likes()
                ->where('user_id', '=', $userId)
                ->first();

            if (!$like) return;

            $like->delete();
        }

        $this->decrementLikeCount();
    }

    /**
     * Has the currently logged in user already "flagged" the current object
     *
     * @param string $userId
     * @return boolean
     */
    public function alreadyLiked($userId = null)
    {
        if (is_null($userId)) {
            $userId = $this->loggedInUserId();
        }

        return (bool) $this->likes()
            ->where('user_id', '=', $userId)
            ->count();
    }

    /**
     * Private. Increment the total like count stored in the counter
     */
    private function incrementLikeCount()
    {
        $counter = $this->likeCounter()->first();

        if ($counter) {
            $counter->count++;
            $counter->save();
        } else {
            $counter = new LikeCounter();
            $counter->count = 1;
            $this->likeCounter()->save($counter);
        }
    }

    /**
     * Private. Decrement the total like count stored in the counter
     */
    private function decrementLikeCount()
    {
        $counter = $this->likeCounter()->first();

        if ($counter) {
            $counter->count--;
            if ($counter->count) {
                $counter->save();
            } else {
                $counter->delete();
            }
        }
    }

    /**
     * Fetch the primary ID of the currently logged in user
     * @return number
     */
    public function loggedInUserId()
    {
        if (app()->environment() === 'local' || app()->environment() === 'testing') {
            return 1;
        }

        return \Auth::id();
    }
}
