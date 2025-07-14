# TaskLinker

## Prérequis

Créer le réseau local `tasklinker_lan` via la commande suivante :

```bash
docker network create tasklinker_lan
```

Pour lancer le projet, utilisez la commande suivante :

```bash
docker compose up -d --build
```

Le site est ouvert sur le port 8000.

Entrer dans le conteneur `tl_app` :

```bash
docker exec -it tl_app /bin/bash
```

> Si besoin: Forcez l'installation des dépendances avec la commande suivante :

```bash
composer install -o
```
