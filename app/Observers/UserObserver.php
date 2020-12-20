<?php

namespace App\Observers;

use App\Models\Preference;
use App\Models\Profile;
use App\Models\User;
use PHP_CodeSniffer\Standards\MySource\Tests\PHP\EvalObjectFactoryUnitTest;
use Queue;

class UserObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  User  $user
     * @return void
     */
    public function created(User $user)
    {
        // create profile
        $profile = Profile::firstOrNew([
            'user_id' => $user->id,
        ]);
        $user->profile()->save($profile);

        // create preference
        $preference = Preference::firstOrNew([
            'user_id' => $user->id,
            'notifications' => [
                'appointment' => true,
                'chat' => true,
                'comment' => false,
                'like' => false,
            ],
        ]);
        $user->preference()->save($preference);

        // sync model to elastic
        $payload = $this->indexApiPayload($user);
        Queue::connection('elastic')
            ->pushRaw(json_encode($payload), 'elastic');
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  User  $user
     * @return void
     */
    public function updated(User $user)
    {
        if ($user->isDirty('is_active')) {
            $payload = $this->updateApiPayload($user, 'active', $user->is_active);
            Queue::connection('elastic')
                ->pushRaw(json_encode($payload), 'elastic');
        }
        if ($user->isDirty('gender')) {
            $payload = $this->updateApiPayload($user, 'gender', $user->gender);
            Queue::connection('elastic')
                ->pushRaw(json_encode($payload), 'elastic');
        }
        if ($user->isDirty('name')) {
            $payload = $this->updateApiPayload($user, 'name', $user->name);
            Queue::connection('elastic')
                ->pushRaw(json_encode($payload), 'elastic');
        }
        if ($user->isDirty('dob')) {
            $payload = $this->updateApiPayload($user, 'dob', $user->dob->format('Y-m-d'));
            Queue::connection('elastic')
                ->pushRaw(json_encode($payload), 'elastic');
        }
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }

    // ================================================================================
    // private methods
    // ================================================================================

    /**
     * @param User $user
     * @return array
     */
    private function indexApiPayload(User $user): array
    {
        return [
            'api' => 'index',
            'index' => 'users_v1',
            'id' => $user->id,
            'body' => [
                'username' => $user->username,
                'name' => $user->name,
                'gender' => $user->gender,
                'dob' => $user->dob->format('Y-m-d'),
                'height' => $user->profile->height,
                'location' => "{$user->profile->latitude},{$user->profile->longitude}",
                'smoking' => $user->profile->smoking,
                'drinking' => $user->profile->drinking,
                'updated_at' => $user->updated_at->toJSON(),
                'active' => $user->is_active,
            ]
        ];
    }

    /**
     * @param User $user
     * @param string $field
     * @param mixed $value
     * @return array
     */
    private function updateApiPayload(User $user, string $field, $value): array
    {
        return [
            'api' => 'update',
            'index' => 'users_v1',
            'id' => $user->id,
            'body' => [
                'script' => [
                    'lang' => 'painless',
                    'source' => <<<EOT
if (ctx._source.{$field} == params.x) {ctx.op = 'noop'}
ctx._source.{$field} = params.x
EOT
                    ,
                    'params' => [
                        'x' => $value
                    ]
                ],
            ],
        ];
    }

    /**
     * @param User $user
     * @return array
     */
    private function deleteApiPayload(User $user): array
    {
        return [
            'api' => 'delete',
            'index' => 'users_v1',
            'id' => $user->id,
        ];
    }
}
