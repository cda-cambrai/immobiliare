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

## Recherche en JavaScript

On va implémenter une recherche en JavaScript avec le rechargement dynamique des annonces.

- Ecouter un événement en JS sur la barre de recherche dès qu'on saisit au clavier.
- A chaque fois qu'on saisit quelque chose, on doit faire un appel AJAX à Symfony.
- L'appel AJAX va se faire sur une nouvelle route (on peut dire endpoint) `/api/search/toto` sur laquelle
  on devra récupèrer la valeur saisie.
- Cette route devra renvoyer la liste des annonces (en JSON) qui correspondent à la recherche.
- Quand on aura le JSON, en JS, on devra mettre à jour le DOM, c'est à dire la liste des annonces.
