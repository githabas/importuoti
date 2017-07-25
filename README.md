What's inside?
--------------

Imports .csv file in db. 
Displays imported records in a table and alows inline editing.

.csv file example:
 
 John|Peter|Saulius|Aušra<br>
 asqwpo|nunio][]|6521|.,m<br>
 o||21|.,m

How to install this project
---------------------------

  1. `git clone https://github.com/githabas/importuoti.git`
  1. `cd importuoti`
  1. `composer install`
  1. `php bin/console doctrine:database:create`
  1. `php bin/console doctrine:schema:create`
  1. `php bin/console server:run`
  1. Browse `http://127.0.0.1:8000/`
