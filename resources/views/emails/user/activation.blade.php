@component('mail::message')
Welcome

Thank you for signing up.

@component('mail::button', ['url' => $url])
Activate Account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
