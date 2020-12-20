<?php

namespace App\Handlers;

use App\Models\Appointment;
use App\Scopings\Scopes\Appointments\AgeScope;
use App\Scopings\Scopes\Appointments\ThemeScope;
use App\Scopings\Scopes\Appointments\GenderScope;
use App\Scopings\Scopes\Appointments\LikeScope;
use App\Scopings\Scopes\Appointments\RegionScope;
use App\Scopings\Scopes\Appointments\VerificationScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class AppointmentHandler
{
    /**
     * fetch all experiences
     *
     * @return LengthAwarePaginator
     */
    public function fetch(): LengthAwarePaginator
    {
        return Appointment::with([
            'user',
            'venue',
            'regions',
            'parties.user',
            'parties.friend',
            'favoriters',
        ])
            ->withScopes($this->scopes())
            ->where('is_active', true)
            ->orderBy('id', 'DESC')
            ->paginate(10);
    }

    /**
     * find appointment by id
     *
     * @param int $id
     * @return Appointment|Model
     */
    public function findById(int $id)
    {
        return Appointment::with([
            'user',
            'venue',
            'regions',
            'parties.user',
            'parties.friend',
            'favoriters',
        ])
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * fetch appointments by userId
     *
     * @param int $userId
     * @return LengthAwarePaginator
     */
    public function fetchByHostUserId(int $userId): LengthAwarePaginator
    {
        $builder = Appointment::with([
            'user',
            'venue',
            'regions',
            'parties.user',
            'parties.friend',
            'favoriters',
        ])
            ->where('user_id', $userId)
            ->Orwhere(function ($subquery) use ($userId) {
                $subquery->whereIn('id', function (Builder $query) use ($userId) {
                    $query
                        ->select('appointment_id')
                        ->from('parties')
                        ->where('is_host', true)
                        ->where(function ($query) use ($userId) {
                            $query->where('user_id', $userId)
                                ->orWhere('friend_id', $userId);
                        });
                });
            })
            ->orderBy('id', 'DESC');

        return $builder->paginate(10);
    }

    /**
     * fetch appointments by userId
     *
     * @param int $userId
     * @return LengthAwarePaginator
     */
    public function fetchByJoinUserId(int $userId): LengthAwarePaginator
    {
        $builder = Appointment::with([
            'user',
            'venue',
            'regions',
            'parties.user',
            'parties.friend',
            'favoriters',
        ])
            ->whereIn('id', function (Builder $query) use ($userId) {
                $query
                    ->select('appointment_id')
                    ->from('parties')
                    ->where('is_host', false)
                    ->where(function ($query) use ($userId) {
                        $query->where('user_id', $userId)
                            ->orWhere('friend_id', $userId);
                    });
            })
            ->orderBy('id', 'DESC');

        return $builder->paginate(10);
    }

    // ================================================================================
    // protected methods
    // ================================================================================

    protected function scopes()
    {
        return [
            'theme' => new ThemeScope(),
            'age' => new AgeScope(),
            'gender' => new GenderScope(),
            'region' => new RegionScope(),
            'like' => new LikeScope(),
            'verification' => new VerificationScope(),
        ];
    }

    // ================================================================================
    // not being used.
    // ================================================================================

    /**
     * fetch appointments by category id
     *
     * @param int $id
     * @return LengthAwarePaginator
     */
    public function findByCategoryId(int $id): LengthAwarePaginator
    {
        $builder = Appointment::with([
            'user',
            'venue',
            'regions',
            'parties.user',
            'parties.friend',
            'favoriters',
        ])
            ->select(['appointments.*'])
            ->join(
                'categories',
                'categories.id',
                '=',
                'activity_posts.category_id'
            )
            ->where('categories.id', $id)
            ->orderBy('activity_posts.id', 'DESC');

        return $builder->paginate(10);
    }

    /**
     * fetch activities by category ids
     *
     * @param array $ids
     * @return LengthAwarePaginator
     */
    public function fetchFeedsByIds(array $ids): LengthAwarePaginator
    {
        $builder = Appointment::with([
            'user',
            'venue',
            'regions',
            'parties.user',
            'parties.friend',
            'favoriters',
        ])
            ->select(['activity_posts.*'])
            ->join(
                'categories',
                'categories.id',
                '=',
                'activity_posts.category_id'
            )
            ->whereIn('categories.id', $ids)
            ->orderBy('activity_posts.id', 'DESC');

        return $builder->paginate(10);
    }

    /**
     * read activities by category typeId
     *
     * @param null $type
     * @return LengthAwarePaginator
     */
    public function fetchFeedsByType($type = null): LengthAwarePaginator
    {
        $builder = Appointment::with([
            'user',
            'venue',
            'regions',
            'parties.user',
            'parties.friend',
            'favoriters',
        ]);

        if ($type) {
            $builder
                ->select(['activity_posts.*'])
                ->join(
                    'categories',
                    'categories.id',
                    '=',
                    'activity_posts.category_id'
                )
                ->where('categories.type', $type);
        }

        $builder
            ->orderBy('activity_posts.id', 'DESC');

        return $builder->paginate(10);
    }
}
