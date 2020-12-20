@component('mail::message')
# {{ Lang::get('Thank you for joining us.') }}

{{ Lang::get('WH is a social platform exclusively designed to mingle with like-minded people.') }}

@component('mail::panel')
    {{ Lang::get('Unlike other social platforms focusing on people living in the vicinity, you can meet someone who shares your passion and interest no matter where they live.') }}
@endcomponent

{{ Lang::get('Life is too short to be stuck in a neighborhood!?') }}

{{ Lang::get('Thanks!') }}<br>
{{ config('app.name') }}
@endcomponent
