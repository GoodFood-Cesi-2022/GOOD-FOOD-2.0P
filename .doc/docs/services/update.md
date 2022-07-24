# Mise à jours

Quand vous récupérez le code depuis le dépôt il est nécessaire de mettre à jour l'environnement de développement ainsi que les composants de l'application.

## Mise à jour de l'environnement

```bash
docker-compose build
```

## Mise à jour de l'application

!!! tips "Lancez les conteneurs Docker avec `docker-compose up -d`"

```bash
docker-compose exec api composer install
docker-compose exec api php artisan migrate
docker-compose exec api php artisan db:seed
```