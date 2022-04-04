@component('mail::message')

{{ __('Bienvenue sur GOODFOOD') }}

{{ __('Vous êtes invité à finaliser votre compte en cliquant sur le bouton ci-dessous') }}

@component('mail::button', ['url' => route('account.confirm.view', ['token' => $token])])
{{ __('Valider votre compte') }}
@endcomponent


{{ __("Si vous n'êtes pas concerné par cet mail, vous pouvez l'ignorer.") }}
@endcomponent