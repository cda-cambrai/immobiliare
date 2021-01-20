# Immobiliare

Après avoir récupéré le projet Symfony, ne pas oublier d'installer les dépendances :

```
composer install
```

Ne pas oublier de configurer la base de données dans le fichier ```.env``` :

```
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
php bin/console doctrine:fixtures:load
```
