@component('mail::message')

{{ __('Bonjour,') }}

{{ __('La recette **:recipe_name** a été supprimée.', compact('recipe_name')) }}

{{ __('Elle ne sera plus à la carte à compter du :date', compact('date')) }}

{{ __("Si vous n'êtes pas concerné par cet email, vous pouvez l'ignorer.") }}
@endcomponent