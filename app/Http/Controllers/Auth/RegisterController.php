<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:api');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            // 'name' => ['required', 'alpha_dash', 'max:80', 'unique:users'],
            'email' => ['required', 'email', 'max:80', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'uuid' => ['required', 'string'],
            'device' => ['required', 'string'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'uuid' => $data['uuid'],
            'device' => $data['device'],
        ]);
    }

    /**
     * The user has been registered.
     *
     * @param  Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        $meta = [];

        //
        // meta 정보에 redirect 키 값을 추가 (app 상의 route 와 연계하여 app 의 middleware 에서 navigation 이동)
        //
        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
            $meta = [
                'redirect' => '/verify',
            ];
        }

        $token = (string) $this->guard()->getToken();
        $expiration = $this->guard()->getPayload()->get('exp');

        return (new UserResource($user))->additional([
            'meta' => array_merge($meta, [
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $expiration - time(),
            ])
        ]);
    }
}
