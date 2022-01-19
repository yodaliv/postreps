@component('mail::message')
# New agent register without office

Agent {{$agent->user->name}} has just registered but doesn't have any office assigned.

{{-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
