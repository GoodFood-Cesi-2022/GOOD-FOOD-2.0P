# Permissions

Liste des permissions pour éditer un model. 

## Récupérer les permissions via les appels API

Pour calculer si l'utilisateur à le droit d'effectuer tel ou tel action sur la resource demandée vous devez préciser dans les paramètres de la requête le tableau de valeur `abilities[]=`.

Par exemple pour savoir si l'utilisateur courant peut créer une recette

`GET` : `/api/users/current?abilities[]=recipe.create`

La réponse comportera en + des attributs classiques la clé `abilities` avec la résolution des permissions

```json
{
    "abilities": {
        "recipe.create": true
    }
}
```

Autre exemple pour savoir si l'utilisateur courant peut supprimer la recette

`GET` : `/api/recipes/9?abilities[]=delete`

```json
{
    "abilities": {
        "delete": false
    }
}
```

!!! warning "Si la méthode n'existe pas dans la policy du model alors une exception est levée"


## Règles


### User

|Règle|Code|Description|
|-|-|-|
|Créer un utilisateur|`create`|Determine si l'utilisateur courant peut créer des utilisateurs|
|Créer une recette|`recipe.create`|Determine si l'utilisateur courant peut créer une recette|
|Créer un ingrédient|`ingredient.create`|Determine si l'utilisateur courant peut créer un ingrédient|
|Créer un fichier|`file.upload`|Determine si l'utilisateur courant peut uploader un fichier|
|Créer un type d'ingrédient|`ingredient_type.create`|Determine si un utilisateur peut créer un type d'ingrédient|
|Voir les informations d'un utilisateur|`view`|Determine si l'utilisateur courant peut voir les informations de l'utilisateur|
|Voir n'importe quel utilisateur|`viewAny`|Determine si l'utilisateur courant peut voir la liste des utilisateurs|
|Attacher un rôle|`addRole`|Determine si l'utilisateur courant peut attacher un rôle à un autre utilisateur|
|Détacher un rôle|`detachRole`|Determine si l'utilisateur courant peut détacher un rôle d'un autre utilisateur|
|Voir les rôles|`viewRoles`|Determine si l'utilisateur courant peut voir les rôles des autres utilisateurs|
|Voir les types d'ingrédients|`ingredient_type.viewAny`| Détermine si l'utilisateur courant peut récupérer les types d'ingrédients|


### Ingredient

|Règle|Code|Description|
|-|-|-|
|Mettre à jour l'ingrédient|`update`|Determine si l'utilisateur courant peut mettre à jour l'ingrédient|
|Supprimer l'ingrédient|`delete`|Determine si l'utilisateur courant peut supprimer l'ingrédient|


### Recipe

|Règle|Code|Description|
|-|-|-|
|Mettre à jour la recette|`update`|Determine si l'utilisateur courant peut mettre à jour la recette|
|Supprimer la recette|`delete`|Determine si l'utilisateur courant peut supprimer la recette|
|Mettre la recette en star|`star`|Determine si l'utilisateur courant peut rendre la recette obligatoire auprès des franchisés|
|Unstar la recette|`unstar`|Determine si l'utilisateur courant peut rendre une recette obligatoire en normal|
|Attacher une photo|`attachPicture`|Determine si l'utilisateur courant peut attacher une photo à la recette|
|Détacher une photo|`detachPicture`|Determine si l'utilisateur courant peut détacher une photo de la recette|
