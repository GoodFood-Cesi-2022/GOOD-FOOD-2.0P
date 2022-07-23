# Sécurité

L'API est sécurisée par le protocole d'authentification OAUTH2, il est implémenté par l'extension Laravel `Passport`. Un conteneur dédié sert de `OAuth Provider` qui se chargera de créer les tokens et d'authentifier les utilisateurs. L'API se chargera de vérfier les `tokens` des requêtes.

Terminologie OAuth: [https://oauth2.thephpleague.com](https://oauth2.thephpleague.com/terminology/)

## Installation

Pour utiliser le OAuthProvider assurez vous d'avoir lancé le conteneur `goodfood-oauth-provider`.

Puis en dans la console du conteneur exécutez les commandes suivantes:

Mise à jour de la BDD

```bash
php artisan migrate
```

Génération des clés de chiffrement

```bash
php artisan passport:keys
```

Vous pouvez désormais générer des clients pour l'API.

## Configuration

Les configurations du OAuthProvider

### Hashing

Les clés secretes des clients sont hachées dans tous les environnements, sauf en local. Une fois la clé secrète générée elle ne peut-être retrouvée.

### Scopes

Aucun scope pour l'API, l'ensemble des routes sont accessibles pour tous les clients.


### Clients 

Pour authentifier les applications mobile et web il est nécessaire de créer 2 clients et 1 pour le développement.

!!! info "Pensez à sauvegarder les clés secrètes"
    En environnement local vous pourrez les retrouver dans la table `oauth_clients`

#### GoodFood Mobile

```bash
php artisan passport:client --public
```

#### GoodFood Web

```bash
php artisan passport:client --public
```

#### Développement

```bash
php artisan passport:client --public
```




## Utilisation

Les informations utiles pour demander un token. Pour suivre les recommendations du protocole OAUTH 2 nous allons utiliser le workflow `Authorization Code Grant with PKCE` recommandé par [oauth2.thephpleague.com](https://oauth2.thephpleague.com/authorization-server/which-grant/).

!!! warning "Avec cette méthode l'authentification est faite sur le serveur OAuth2 et non sur le client"



