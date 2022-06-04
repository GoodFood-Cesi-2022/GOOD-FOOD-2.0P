@component('mail::message')

{{ __('Bonjour,') }}

{{ __('Une nouvelle recette star a été ajoutée par GoodFood.') }}

## {{ $recipe_name }}

{{ __('Vous avez jusqu\'au **:date** pour vous préparer, à compter de cette date la nouvelle recette sera automatiquement ajoutée aux plats disponibles pour les clients.', ['date' => $date]) }}



{{ __("Si vous n'êtes pas concerné par cet email, vous pouvez l'ignorer.") }}
@endcomponent