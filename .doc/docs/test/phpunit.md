# Test

Pour les tests unitaires et fonctionnels nous utilisons les utilitaires proposés par Laravel [Testing](https://laravel.com/docs/9.x/testing). Cet utilitaire encapsule le framework de test PHP [PHPUnit](https://phpunit.readthedocs.io/en/9.5/).


## Configuration

Le contexte d'exécution des tests est géré par le fichier `/phpunit.xml`. Pour ce faire l'ensemble des services sont mockés ou temporaire (cache, sessions, storage, ...) une exception est à noter pour la base de données. Pour rendre les tests plus réalistes le service utilise un service de base de données de test.


## Structure

Les tests sont disponibles dans le dossier `/tests`, à l'intérieur de se dossier ils sont organisés de la façon suivante:

* `Feature` : Les tests de fonctionnalités
    * `API`: Tous les tests de l'API
    * `Auth`: Tous les tests pour l'authentification
* `Unit`: Les tests unitaires


## Authentification

Pour faciliter l'authentification des utilisateurs et la gestion des rôles il est possible d'utiliser les méthodes suivantes pour exécuter un test en tant que qu'utilisateur ayant un rôle `client`, `contractor` ou `goodfood`. Ces méthodes sont définies dans la classe `ApiCase`

### `actingAsClient`

Permet de s'authentifier comme un utilisateur d'API avec le rôle par défaut `client`

### `actingAsContractor`

Permet de s'authentifier comme un utilisateur d'API avec le rôle par défaut `contractor`

### `actingAsGoodfood`

Permet de s'authentifier comme un utilisateur d'API avec le rôle par défaut `goodfood`

### Exemple

```php
<?php

namespace Tests\Feature\Api\Example;

use Tests\Feature\Api\ApiCase;

class ExampleTest extends ApiCase
{

    public function text_example_api_endpoint() {

        // Unauthenticated

        $user = $this->actingAsContractor();

        // Authenticated as user with contractor role

    }

}

```


## Exécutions

### Local

Les tests peuvent être lancés en local pour le développement avec la commande suivante:

```bash
php artisan test
```

### CI

L'exécution des tests est incluse dans le pipeline d'intégration continue, ils sont exécutés dans les cas d'utilisations suivants:

* Commit sur une branche
    * feature/*
    * release/*
* Commit sur PR
* Merge d'une PR dans les branches
    * develop
    * main

!!! info "L'exécution des tests dans la CI permet de garantir la non régression et une qualité de code améliorée"
    L'exécution des tests est couplée à l'analyse de sonarcloud qui permet d'avoir une qualité de code optimale.