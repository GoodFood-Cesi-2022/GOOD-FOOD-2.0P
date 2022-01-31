# Services

Liste des conteneurs pour gérer les services de l'application.

## Conteneurs

|Conteneur|Port Ouvert Du Service|Port Interne|Protocole|Description|Env|
|-|-|-|-|-|-|
|api||9000|`TCP`|Worker `php-fpm` pour gérer les requêtes PHP|PROD|
|db|5432|5432|`SQL`|Base de données PostgreSQL pour les données de l'application|PROD|
|cache||6379|`TCP`|Cache de l'API pour les données temporaires et pour la sauvegarde des jobs|PROD|
|nginx|8080|80|`HTTP`| Front pour contacter le service de l'API|PROD|
|search||8108|`HTTP`| Service pour l'indexation et la recherche de résultat|PROD|
|mailhog|8025,1025|8025,1025|`HTTP`,`SMTP`|Faker de boite mail pour tester les mails envoyés de l'application|DEV|
|doc|8009|8000|`HTTP`|Documentation [MKdocs Material](https://squidfunk.github.io/mkdocs-material/) pour la documentation technique|DEV|

### Réseau

L'ensemble des conteneurs sont sur le réseau `goodfood` pour qu'ils puissent communiquer entre eux.

!!! danger "Exception pour le conteneur de doc"

### Stockage

Pour le développement nous utilisons le stockage physique de l'hôte pour enregistrer les fichiers (pas d'API S3 utilisée). Cependant pour prendre en charge l'API S3 il faut obligratoirement passer par la façade [Storage](https://laravel.com/docs/8.x/filesystem) du framework pour pouvoir switch en production.

La base de données possède un volume fixe ainsi que le service search et le cache.


## Conteneur Personnalisé

### API

Le conteneur de l'API est personnalisé. Il est construit à partir du fichier `./Dockerfile`. Il est basé sur l'image `php:8.1-fpm`. 

Il installe les dépendances nécessaires pour le système et active les extensions PHP nécessaires au fonctionnement du framework Laravel. Il récupère de l'image composer la dernière version du gestionnaire de dépendances PHP.

Création de l'utilisateur pour la bonne gestion des droits sur les fichiers.

Pour build l'image

```bash
docker-compose build api
```

### DOC

!!! tip "Par défaut le site est en hot-relive"
    Quand vous mettez à jour la documentation le site se recharge automatiquement.

La configuration est disponible dans le fichier `./doc/Dockerfile`. Pour l'instant il est vide mais il sera peut-être nécessaire de rajouter des extensions ou des étapes de constuction.

