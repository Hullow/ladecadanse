# Changelog


## [3.4.7] Unreleased

### Changed

- [ ] refactor
    - [ ] get genre, tri_agenda params to session

### Removed

- [ ] usage of globals


## [3.4.6] Unreleased

### Fixed

- users - edit : "avec affiliation" value must be sent in submit, rm conditions

### Added

- events : api sending night "fêtes" events of a day (Noctambus)
- tests : setup Codeception and basic API tests

### Changed

- [ ] events form
    - [ ] inscrease width of fields (complément, titre, desc, sites web, prix, prélocs)
    - [ ] put back horaire under date
    - [ ] categories : replace fêtes tooltip by permanent text
    - [ ] lieu "Ou si le lieu n'est pas listé :", one background for div "Nom du lieu", "Adresse", etc.; and more on right
- [ ] tests : documentation revised, completed (readme, strategy, map)
- [ ] update jQuery from 3.6.4 to 3.7.0


## [3.4.5] - 2023-05-18

### Fixed

- users - password reset : in db table rm unique of idPersonne to avoid crash
- users - edit : affiliation text wasn't saved, display "avec affiliation" field only if pertinent
- add ini_set session.gc_probability to enable auto clean of old session in Debian
- UX : added missing icon ext links
- in small screens events lists right overflow

### Added

- tests : added assertions, for most important cases in Selenium suites
- Doc link in menu 1 for admin users

### Changed

- php libraries : whoops 2.15, phpmailer 6.8, phpstan


## [3.4.4] - 2023-04-16

### Fixed

- evenement : crash if lieu not found
- lieu : galerie image upload
- tests : some fixes in Selenium suites

### Added

- tests : in documentation, readme and strategy revised, completed

### Removed

- à propos : inactive in staff

### Security

- update [jquery link "latest"](https://cdn.jsdelivr.net/jquery/latest/jquery.min.js) (actually freezed at 3.2.1) to lastest 3.6.4


## [3.4.3] - 2023-03-22

### Fixed

- home : mobile left col was unusable
- evenement : unpublish auth, in edit ref and prelocations length validation, calendar past days color
- lieux : header; in home, latests added only actives, logo size in mobile
- readme : create admin sql

### Added

- basic end to end tests suites for Selenium IDE

### Changed

- TESTS.md v1.1
- in home, agenda links iCal, report more visible


## [3.4.2] - 2023-03-12

### Fixed

- evenement
    - in calendar, event's date (instead of today)
    - handle if img not found

### Added

- TESTS.md v1

### Changed

- events : in edit form, larger width and inputs, more help texts for better data entered
- darken `a:visited`


## [3.4.1] - 2023-03-03

### Fixed

- agenda : event categories title weren't displayed in the list
- user levels usage
- date functions to avoid notices

### Added

- in calendar, past days half transparent
- evenement : `<time>` on event date

### Security

- [x] restored honeypot in evenement-report


## [3.4.0] - 2023-02-27

### Fixed

- events : copy

### Added

- `UserLevel` class introduced (replaces useless table `groupes`)
- [Whoops](https://github.com/filp/whoops) error handler

### Changed

- users : level 12 (member) disabled
- sessions saved in `var/sessions`
- config for events files from images/ to web/uploads/evenements (and gitignore)
- refactor
    - data files to new dir `resources`
    - classes in `libraries` : cleaning, typehints
    - `dates.php` more generic as `utils_functions.php` and moved in `sanitizeForHtml()`
    - agenda.php to evenement-agenda.php, event to evenement-actions
    - _footer.php externalize js
    - _header.php mv script jquery to footer

### Removed

- useless fields of user (ip, session, nom, prenom, adresse, telephone, URL, notification_commentaires, remarque), personne_organisateur (role), evenement (URL1, URL2), lieu (horaire_evenement, entree, telephone, email, acces_tpg, plan, actif), organisateur (telephone)
- users : level 10
- comments (completely)
- favorites (completely)
- enlightn/security-checker

## [3.3.0] - 2023-02-19

### Fixed

- search : mots-vides.txt in utf-8

### Changed

- lieux home like organizers
- Matomo tracking code modernized and for tools.ladecadanse.ch/matomo
- refactor
    -  bootstrap, config
        - purge, rename global url, rep vars
        - class to manage region user choice
        - env : replace remaining env var by const, document
    - table temp -> user_reset_requests
    - url queries for calendar simplified
    - misc

### Removed

- cache
- breves
- favorites
- adding comments
- lieux : document upload
- app : index.html, utf8-to-utf8mb4
- events : tiny flyers, document upload
- header files (home latests, lieu, organisateur)
- rss : even comments, lieux descriptions

### Security
- rm display of mysql errors

## [3.2.6] - 2023-02-05

### Fixed

- misc in agenda, users, contact
- gitignore

### Added

- setup [PHPMailer](https://github.com/PHPMailer/PHPMailer) (replaces PEAR Mail)
- Docker compose recipe
- analyzers setup : PHPCompatibility, phpstan, security checker
- git-ftp setup
- home : Debout les braves banner
- propose event : intro clarifications
- event form : clarifications for ask organisation registration
- changelog
- TESTS.md
- editorconfig

### Changed

- composer : complete needed PHP extensions
- php errors and timezone config externalized
- readme - partially rewritten, completed
- Zebra datepicker 1.9.19
- logs dir

### Removed

- PEAR Mail
- php obsolete functions

### Security
- rm master key

## [3.2.5] - 2023-01-12

### Fixed

- mailing with Message-ID in header (problem with Gmail rejection #58 )
- organisateurs : access to "lz" menu #55
- password reset avoid URL token rejected

### Changed

- better names of files
- better organisation
- moved most of functions into classes
- namespaces and autolading
- `env_model.php` (ex `params_model.php`) completed
- cleaning of useless code
- clearer README
- php 7.4

## [3.2.4] - 2021-12-27

### Fixed

- remove event text styling feature to avoid layout break
-  query parameters handling
- better naming of dirs to ignore by git

### Changed

- remove old unused jquery, chosen dependencies js files


## [3.2.3] - 2021-11-21

### Fixed

- propose event : clarifications
- event form : clarifications for ask organisation registration

## [3.2.2] - 2021-11-21

### Added

- js alert in forms if user selects a file to upload > 2 Mb
- "prochains événements" in home page title

## [3.2.1] - 2019-12-21

### Added

- compat with db utf8mb4 encoding

### Fixed

- misc

### Changed

- updates of libs

## [3.2] - 2019-06-23

### Added

- better experience for visitors, contributors and admins

### Fixed

- fixes and improvements of old v3 issues

Details in issues of Milestone for v3.2 : https://github.com/agilare/ladecadanse/milestone/1?closed=1
