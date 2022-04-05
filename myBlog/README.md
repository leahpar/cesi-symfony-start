# Mon 1er projet Symfony

## Installation :

Clone du projet

```
git clone git@github.com:leahpar/cesi-symfony-start.git
cd cesi-symfony-start/myBlog
```

Créer le fichier `.env.local`
Et compléter les infos nécessaires (accès BDD...)

installation des dépendantes

```
composer install
```

Création base de données (si pas déjà fait)

```
php bin/console doctrine:database:create
```

Mise à jour structure de la base de données (si pas déjà fait)

```
php bin/console doctrine:schema:update --dump-sql
php bin/console doctrine:schema:update --force
```

Démarrage du serveur web symfony (sauf si apache/nginx...)

```
symfony server:start -d
```

**Enjoy !**

Arrêt du serveur web

```
symfony server:stop
```
