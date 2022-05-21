# Application


## Modes

L'application possède plusieurs états qui conditionnent son fonctionnement.

### Mode `Configuration`

En mode configuration les règles de gestion de certaines parties de l'application sont différentes. Par exemple certains mails ne sont pas envoyés.


### Mode `Normal`

Ce mode est le mode normal de l'application.


### Mode `Maintenance`

Ce mode permet de mettre l'application en maintenance et afficher une page aux utilisateurs. Les requêtes reçues par l'API renverront un message d'erreur indiquant que l'application est en maintenance.