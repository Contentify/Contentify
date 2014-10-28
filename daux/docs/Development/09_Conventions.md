Contentify uses several conventions you should be aware of.

## Module Names

If module names refer to entities, e. g. "Game", prefer to pluralize them: "games".

## Config

Contentify extends Laravel's config class. Config values may be stored in config files in `app/config/` (or `app/config/packages/`) or inside the database. To avoid collisions, please use a namespace prefix. Example config key name:
```php
$title = Config::get('app.title');
```
Application keys use the namespace `app`. Module keys should use `::` as namespace seperator:
```php
Config::store('cookies::minutesToBake', 5);
```
## Database: Table And Attribute Names

If table names refer to models, e. g. "Game", prefer to pluralize and lowercase them: "games". Use underscores to seperate words: "delicious_cookies" Also use underscores to seperate words in attribute names.

> If a model belongs to a certain category (a news post belongs to a news category) the models / tables that are part of the default modules are named `<model>cats` (so the table for the news categories is named newscats).

## Database: Semantic Attribute Names

SQL does not offer an explicit way to give attributes a semantic meaning. Laravel and Contentify may give attributes a meaning depending on their names ("semantic names"):

* **id**: Entity id (*integer*, not null)
* **title**: Entity title (*string*, not null)
* **created_at**: Time of creation (*timestamp*)
* **updated_at**: Time of last update (*timestamp*)
* **deleted_at**: Time of deletion, if "soft delete" is enabled (*timestamp*)
* **creator_id**: User-ID of the entity creator (*integer*)
* **updater_id**: User-ID of the user doing the latest update (*integer*)
* **access_counter**: Counts accesses (*integer*)

## Models

Please use English names for models - or at least a name that Laravel can singularize and pluralize.

To seperate a "model class" and a "model object" we use the term "model class" here in Contentiverse and if we use the term "model" it refers to a "model object". A variable called "model class" contains the name of a model class with its namespace, whereas a variable called "model name" contains the name of a model class without the namespace.

## Session Variables

* **captchaCode**: Captcha code input
* **_message**: A flash message
* **redirect**: Redirect to this URL
* **recycleBinMode**: Display deleted entities?
* **_token**: CSRF token

## Caching

When putting data into the cache you should add a namespace to the key's name:

    Cache::put('animals.unicorns.123', $unicorn, 60);

This will help to avoid collisions. You may even prefix all you caching keys with a unique prefix such as `app` or `my`:

    Cache::put('app.animals.unicorns.123', $unicorn, 60);

## Form Input Names

* **_createdat**: Time of form creation (spam protection)
* **_token**: CSRF token

## Code Style

Contentify tries to stay close to Laravel's code style. This is very much equivalent to [PSR coding standards](https://github.com/php-fig/fig-standards/tree/master/accepted). We encourage you to stick to these standards to ensure good code quality.
