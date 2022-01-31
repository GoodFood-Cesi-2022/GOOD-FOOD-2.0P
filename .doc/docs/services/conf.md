# Configuration

## Conteneurs

Les conteneurs sont définis dans le fichier `./docker-compose.yml`.

Il utilise le fichier `./.env` pour récupérer des variables d'environnement.

Les configurations des services docker (nginx, postgreSQL, etc...) sont dans le dossier `./.docker-compose`


## Application

L'application Laravel utilise le fichier `./.env` pour s'initialiser. Tous les autres fichiers de configuration sont disponibles dans le dossier `./config/`
