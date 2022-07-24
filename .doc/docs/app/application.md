# Application


## Modes

L'application possède plusieurs états qui conditionnent son fonctionnement.

Pour changer le mode de l'application il faut utiliser cette commande

```bash
php artisan app:mode $MODE
```

Valeur accepter pour le $MODE: `configuration` ou `normal`.

### Mode `Configuration`

En mode configuration les règles de gestion de certaines parties de l'application sont différentes. Par exemple certains mails ne sont pas envoyés.


### Mode `Normal`

Ce mode est le mode normal de l'application.


### Mode `Maintenance`

Ce mode permet de mettre l'application en maintenance et afficher une page aux utilisateurs. Les requêtes reçues par l'API renverront un message d'erreur indiquant que l'application est en maintenance.

Pour mettre en maintenance vous devez utiliser cette commande

```bash
php artisan down
```