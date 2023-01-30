# La décadanse
📅 Agenda culturel local

> **Warning**
> En raison d'une grande partie de code legacy, et pour des raisons de sécurité, ne déployez pas cette application sur des serveurs publics. La modernisation est en cours, vous pouvez [contribuer](README.md#contribuer)

La décadanse est un site web qui présente aux visiteurs une sélection d'événements culturels locaux et accessibles. Il est actuellement [déployé pour Genève et Lausanne](https://ladecadanse.darksite.ch/)

Les organisateurs d'événements ont la possibilité de s'inscrire puis de se présenter et annoncer leurs événements.

Les principales sections du site sont :
- un **agenda d'événements**, chacun de ceux-ci ayant sa fiche détaillée accompagnée de quelques services (commentaires, signaler une erreur, format iCal...)
- un répertoire des **Lieux** où se déroulent des événements, avec détails, présentation, photos
- un répertoire des **Organisateurs d'événements**, similaire aux Lieux
- un **back-office** permettant de gérer les diverses entités du site : utilisateurs, événements, lieux, organisateurs, etc.

## Installation locale

### Prérequis
- Apache 2.4
- PHP 7.4 (avec les extensions `fileinfo`, `mysqli`, `mbstring`, `gd`)
- Composer
- MariaDB 10/MySQL 5.7

### Manuelle
1. cloner la branche `master`
1. `composer install`
1. dans un fichier de configuration Apache (`.htaccess` ou autre) définir le décalage horaire par défaut PHP, par ex. :
    ```ini
    php_value date.timezone 'Europe/Zurich'
    ```
1. base de données 
    1. créer une base de données avec `COLLATE 'utf8mb4_unicode_ci'` par ex. 
        ```sql        
        CREATE DATABASE `ladecadanse` /*!40100 COLLATE 'utf8mb4_unicode_ci' */;
        ```
    1. créer un utilisateur avec les droits suffisants sur cette base de données, par ex.
        ```sql        
        CREATE USER 'ladecadanse'@'localhost' IDENTIFIED BY 'my-password';
        GRANT USAGE ON *.* TO 'ladecadanse'@'localhost';
        GRANT SELECT, INSERT, DELETE, UPDATE  ON `ladecadanse`.* TO 'ladecadanse'@'localhost';
        ```
    1. importer dans la base de données `app/ladecadanse.sql` (la structure, et les données utiles pour les tables `groupes` et `localite`)
    1. ajouter un 1er utilisateur, l'*admin* (groupe 1) qui vous servira à gérer le site :  
        ```sql
        INSERT INTO `personne` (`idPersonne`, `pseudo`, `mot_de_passe`, `cookie`, `session`, `ip`, `groupe`, `statut`, `nom`, `prenom`, `affiliation`, `adresse`, `region`, `telephone`, `email`, `URL`, `signature`, `avec_affiliation`, `notification_commentaires`, `gds`, `actif`, `remarque`, `dateAjout`, `date_derniere_modif`) VALUES (NULL, 'admin', '', '', '', '', '1', 'actif', '', '', '', '', 'ge', '', '', '', 'pseudo', 'non', 'non', '', '1', '', '0000-00-00 00:00:00.000000', '0000-00-00 00:00:00.000000');
        ```
1. créer le fichier de configuration du site en copiant le modèle `app/env_model.php` vers `app/env.php` et y saisir les valeurs de votre environnement pour (davantage d'explications et exemples se trouvent dans le fichier lui même) :
    - `$rep_absolu`
    - `$url_domaine`
    - `$url_site` 
    - `$param['dbhost']`, etc. les informations de connexion à la base de données (selon point précédent)
    - `define("EMAIL_AUTH_HOST", '');`, etc. : l'authentification et les comptes pour l'envoi d'emails par le site
    - `MASTER_KEY` : un mot de passe "magique" qui fonctionne pour tous les identifiants
    - (optionel) les clés Google pour [Maps](https://developers.google.com/maps/documentation/javascript/get-api-key) (cartes des lieux) et [Recaptcha 3](https://www.google.com/recaptcha/intro/v3.html) (formulaire Proposer un événement)

### Par Docker
Lancer la commande suivante à la racine du projet :
```sh
docker compose up -d
```
Le site ladecadanse est déployé sur localhost:7777.

### Usage
Une fois le site fonctionnel, se connecter avec le login *admin* et le mot de passe `MASTER_KEY` (créés ci-dessus) permet d'ajouter et modifier des événements, lieux, etc. (partie publique) et de les gérer largement (partie back-office)

## Changelog
Voir le [changelog](CHANGELOG.md) et les [releases sur GitHub](https://github.com/agilare/ladecadanse/releases)

## Contribuer
Les Pull requests sont les bienvenues. Pour les changements majeurs, veuillez d'abord ouvrir une Issue pour discuter de ce que vous souhaitez changer.

## License
This work is licensed under CC BY-NC-SA 4.0