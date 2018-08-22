@component('mail::message')
Hola! **{{$manager->fullName}}**

Hemos recivio una notificación para cambiar tu contraseña

<a href="{{route('teacher.showResetForm', [$token])}}" class="button button-blue">
	Cambiar Contraseña
</a>
{{-- @component('mail::button', ['url' => ])
Cambiar contraseña
@endcomponent --}}

Gracias por escogernos,<br>
{{ config('app.name') }}
@endcomponent
