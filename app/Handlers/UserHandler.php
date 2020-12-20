<?php

namespace App\Handlers;

use App\Http\Dtos\SocialUserDto;
use App\Models\SocialProvider;
use App\Models\User;
use App\Scopings\Scopes\Users\GenderScope;
use App\Scopings\Scopes\Users\VerificationScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Jenssegers\Optimus\Optimus;

class UserHandler
{
    /**
     * fetch all users
     * @return LengthAwarePaginator
     */
    public function fetchUsers(): LengthAwarePaginator
    {
        return User::with([
            'profile',
            'socialProviders',
            'media'
        ])
            ->withScopes($this->scopes())
            ->orderBy('id', 'DESC')
            ->paginate(10);
    }

    /**
     * find a model by id
     *
     * @param int $id
     * @param array $relations
     * @return Model|User
     */
    public function findById(
        int $id,
        array $relations = []
    ): User {
        if (count($relations) > 0) {
            return User::with($relations)
                ->where('id', $id)
                ->firstOrFail();
        }

        return User::findOrFail($id);
    }

    /**
     * find a model by hashedId
     *
     * @param int $hashedId
     * @param array $relations
     * @return User
     */
    public function findByHashedId(
        int $hashedId,
        array $relations = []
    ): User {
        $id = app(Optimus::class)->decode($hashedId);

        return $this->findById($id, $relations);
    }

    /**
     * @param string $email
     * @return User|Model
     */
    public function findByEmail(string $email): User
    {
        return User::where('email', $email)
            ->firstOrFail();
    }

    /**
     * @param string $phone
     * @return User|Model
     */
    public function findByPhone(string $phone): User
    {
        return User::where('phone', $phone)
            ->firstOrFail();
    }

    /**
     * @param string $name
     * @return User|Model
     */
    public function findByName(string $name): User
    {
        return User::where('username', $name)
            ->firstOrFail();
    }

    /**
     * @param string $provider
     * @param string $providerId
     * @return User
     */
    public function findBySocialProvider(
        string $provider,
        string $providerId
    ): User {
        $socialProvider = SocialProvider::with(['user'])
            ->where('provider', $provider)
            ->where('provider_id', $providerId)
            ->firstOrFail();

        return $socialProvider->user;
    }

    /**
     * @param SocialUserDto $socialUserDto
     * @return User
     */
    public function createUserFromSocialUserDto(
        SocialUserDto $socialUserDto
    ): User {
        $user = User::create([
            'name' => $socialUserDto->getName(),
            'email' => $socialUserDto->getEmail(),
            'uuid' => $socialUserDto->getUuid(),
            'gender' => $socialUserDto->getGender(),
            'email_verified_at' => Carbon::now()->toDateTimeString(),
            'device' => $socialUserDto->getDevice(),
        ]);

        $dob = $this->getDobString(
            $user,
            $socialUserDto->getDob(),
            $socialUserDto->getAge()
        );

        return $dob ? tap($user)->update(['dob' => $dob]) : $user;
    }

    /**
     * @param User $user
     * @param string $uuid
     * @return User
     */
    public function updateUuid(User $user, string $uuid): User
    {
        return tap($user)
            ->update(['uuid' => $uuid]);
    }

    // ================================================================================
    // protected methods
    // ================================================================================

    protected function scopes()
    {
        return [
            'gender' => new GenderScope(),
            // 'verification' => new VerificationScope(),
        ];
    }

    // ================================================================================
    // helpers
    // ================================================================================

    private function getDobString(User $user, string $dateOfBirth, string $ageRange): ?string
    {
        if (preg_match('/^(\d{2})[-|\/](\d{2})[-|\/](\d{4})/', $dateOfBirth, $matches)) {
            return "{$matches[3]}-{$matches[1]}-{$matches[2]}";
        }
        if (preg_match('/^(\d{2})[-|~](\d{2}|)/', $ageRange, $matches)) {
            $minAge = (int) $matches[1];
            $maxAge = (int) $matches[2];
            $data = [];
            if ($minAge > 18) {
                $data['min_age'] = $minAge;
            }
            if ($maxAge >= $minAge) {
                $data['max_age'] = $maxAge;
            }
            if (count($data) > 0) {
                $user->preference->update($data);
            }
        }
        if (preg_match('/^(\d{2})[-|\/](\d{2})/', $dateOfBirth, $matches)) {
            return "1000-{$matches[1]}-{$matches[2]}";
        }

        return null;
    }
}
