# Fichiers

La documentation sur le stockage des fichiers. L'application offre un seul endpoint pour uploader un fichier. Ce endpoint permet d'upload un fichier binaire dans le système. Une fois le fichier uploadé l'ensemble des endpointq qui requièrent un fichier devront passer un UUID d'un fichier existant.

## Upload d'un fichier

!!! danger "Pour ce endpoint le header `Content-Type` doit-être fixé à `multipart/form-data`"

