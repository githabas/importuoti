What's inside?
--------------

Import application.

How to install this project
---------------------------

  1. `git clone https://antradienio@bitbucket.org/antradienio/imported.git`
  1. `cd imported`
  1. `composer install`
  1. `php bin/console doctrine:database:create`
  1. `php bin/console doctrine:schema:create`
  1. `php bin/console server:run`
  1. Browse `http://127.0.0.1:8000/`
