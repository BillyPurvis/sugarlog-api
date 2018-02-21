
# Getting Started


## Prerequisites

- PHP7+
- MySQL (InnoDB Engine)
- Text Editor / PHPStorm
- Composer
- Git

## Installing

Clone this repo into whatever directory and run `composer install`, assuming you have composer installed 

## Creating database
Doctrine is being used with Symfony Flex, so we'll need to run various commands to set up the ORM. 

`php bin/console d:d:create` will create the database. (If you need to drop it, `php bin/console d:d:drop --force`), ommitting `--force` flag would only close the connections, rather than dropping the database. 

## Migrations
Doctrine uses Entities as models; creating new privates in an entity file won't add new columns / tables to the database. You'll need to run migrations, much like Laravel and Eloquent. Agian, there's short hand commands.  `php bin/console d:m:diff`

First we'll need to create a diff file, which prepares a file for the migration. They'll go to `src/Migrations`. You can directly edit these, but it's not a great idea unless there's an actual need. If you've made a mistake, delete the created migration, clear cache `php bin/console cache:clear` and then edit the entity file, re-run the diff and you'll be good. 

After the diff is created, we'll actually need to run the migration. `php bin/console d:m:mi` will do this. 

## Running Symfony

