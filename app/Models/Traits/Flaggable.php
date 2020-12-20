<?php

namespace App\Models\Traits;

use App\Models\Flag;
use App\Models\FlagCounter;

trait Flaggable {

    /**
     * Fetch only records that currently logged in user has flagged
     */
    public function scopeWhereFlagged($query, $userId = null)
    {
        if (is_null($userId)) {
            $userId = $this->loggedInUserId();
        }

        return $query->whereHas('flags', function($q) use ($userId) {
            $q->where('user_id', '=', $userId);
        });
    }

    /**
     * Populate the $model->flag_count attribute
     */
    public function getFlagCountAttribute()
    {
        return $this->flagCounter ? $this->flagCounter->count : 0;
    }

    /**
     * Collection of the flags on this record
     */
    public function flags()
    {
        return $this->morphMany('\App\Models\Flag', 'flaggable');
    }

    /**
     * Counter is a record that stores the total flags for the morphed record
     */
    public function flagCounter()
    {
        return $this->morphOne('\App\Models\FlagCounter', 'flaggable');
    }

    /**
     * @param int $userId
     * @param string $body
     */
    public function flag(int $userId, string $body)
    {
        if (is_null($userId)) {
            $userId = $this->loggedInUserId();
        }

        if ($userId) {
            $flag = $this->flags()
                ->where('user_id', '=', $userId)
                ->first();

            if ($flag) return;

            $flag = new Flag();
            $flag->user_id = $userId;
            $flag->body = $body;
            $this->flags()->save($flag);
        }

        $this->incrementFlagCount();
    }

    /**
     * Remove a flag from this record for the given user.
     * @param $userId mixed - If null will use currently logged in user.
     */
    public function unflag($userId = null)
    {
        if (is_null($userId)) {
            $userId = $this->loggedInUserId();
        }

        if ($userId) {
            $flag = $this->flags()
                ->where('user_id', '=', $userId)
                ->first();

            if (!$flag) return;

            $flag->delete();
        }

        $this->decrementFlagCount();
    }

    /**
     * Has the currently logged in user already "flagged" the current object
     *
     * @param string $userId
     * @return boolean
     */
    public function alreadyFlagged($userId = null)
    {
        if (is_null($userId)) {
            $userId = $this->loggedInUserId();
        }

        return (bool) $this->flags()
            ->where('user_id', '=', $userId)
            ->count();
    }

    /**
     * Private. Increment the total flag count stored in the counter
     */
    private function incrementFlagCount()
    {
        $counter = $this->flagCounter()->first();
        if ($counter) {
            $counter->count++;
            $counter->save();
        } else {
            $counter = new FlagCounter();
            $counter->count = 1;
            $this->flagCounter()->save($counter);
        }
    }

    /**
     * Private. Decrement the total flag count stored in the counter
     */
    private function decrementFlagCount()
    {
        $counter = $this->flagCounter()->first();

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
