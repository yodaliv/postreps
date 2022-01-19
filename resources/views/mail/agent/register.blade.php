@component('mail::message')
# New Agent register

New agent registration. Agent name: {{$agent->user->name}}

{{-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
