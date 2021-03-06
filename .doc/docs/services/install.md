# Installation 

Tout pour lancer les conteneurs et faire tourner l'application.

## Prérequis

* Docker d'installé

## Construction des conteneurs

### API

```
docker-compose build api
```

### Doc

```
docker-compose build doc
```

### NGINX

!!! tip "Pour les droits utilisateurs"

```bash
docker-compose build nginx
```

### Doctum

```
docker-compose build doctum
```

## Lancement des services

```bash
docker-compose up -d
```

### Installation des dépendances composer

```bash
docker-compose exec api composer install
```

Toutes les resources seront contruites et l'application sera disponible à l'adresse suivante [localhost:8080](http://localhost:8080)

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



## Extras

Une petite liste d'extras

### PGADMIN

Pour consulter la base de données et la gérer via un navigateur

Rendez-vous sur le lien [localhost:5050](http://localhost:5050)

* Login: `admin@admin.admin`
* Password: `admin`

Configuration de la base: 

* Host: `db`
* Port: `5432`
* Username: `goodfood_user`
* Password: `goodfood_user` 