@component('mail::message')
Hello!

New User {{ $user->name }} created.

@component('mail::button', ['url' => 'http://addendum.lt'])
Addendum
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
