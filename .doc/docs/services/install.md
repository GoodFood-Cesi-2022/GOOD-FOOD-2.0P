# Installation 

Tout pour lancer les conteneurs et faire tourner l'application.

## Prérequis

* Docker d'installé

## Construction des conteneurs

```bash
docker-compose build
```

## Lancement des services

```bash
docker-compose up -d
```

### Installation des dépendances composer

```bash
docker-compose exec api composer install
```

### Installation de l'application

```
docker-compose exec api php artisan app:install
```

Toutes les resources seront contruites et l'application sera disponible à l'adresse suivante [localhost:8080](http://localhost:8085)

## Mettre en pause les services

```bash
docker-compose pause
```

## Relancer les services

```bash
docker-compose unpause
```

## Stopper les services

```bash
docker-compose stop
```

## Redémarrer les services

```bash
docker-compose start
```


## Supprimer les conteneurs

```bash
docker-compose down
```

!!! tip "Précisez `--volumes` pour supprimer les données"