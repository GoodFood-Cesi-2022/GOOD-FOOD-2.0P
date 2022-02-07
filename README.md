# GOODFOOD API

Bienvenue sur le repo de l'API GOODFOOD. 

## Démarrage rapide

1. Créez le fichier `./.env` et complétez le avec `./.example.env`
2. Clonez le dépôt.
3. Créer les images

```bash
docker-compose build api
```

```bash
docker-compose build doc
```

4. Lancez les conteneurs

```bash
docker-compose up -d
```

5. Installation des dépendances composer

```bash
docker-compose exec api composer install
```

Vérifier l'installation [localhost:8080](http://localhost:8080)


## Documentation

Vous pouvez accèder à la documentation développeur [localhost:8009](http://localhost:8009)