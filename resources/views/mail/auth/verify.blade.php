@component('mail::message')
# {{ Lang::get('OTP for email verification.') }}

{{ Lang::get('Please enter the following OTP on the verification screen in App.') }}

@component('mail::panel')
    {{ $otp }}
@endcomponent

- {{ Lang::get('OTP will only be valid for 15 minutes.') }}
- {{ Lang::get('If you did not create an account, no further action is required.') }}

{{ Lang::get('Thanks!') }}<br>
{{ config('app.name') }}
@endcomponent
