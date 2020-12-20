@component('mail::message')
# {{ Lang::get('Reset your password.') }}

{{ Lang::get('You are receiving this OTP because we received a password reset request for your account.') }}

@component('mail::panel')
    {{ $otp }}
@endcomponent

- {{ Lang::get('OTP will only be valid for 15 minutes.') }}
- {{ Lang::get('If you did not request a password reset, no further action is required.') }}

{{ Lang::get('Thanks!') }}<br>
{{ config('app.name') }}
@endcomponent
