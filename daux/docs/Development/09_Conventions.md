Contentify uses several conventions you should be aware of.

## Module Names

If module names refer to entities, e. g. "Game", prefer to pluralize them: "games". Some names are reserved. Please do not use them for custom modules:

* comments
* contentify

## Config

Contentify extends Laravel's config class. Config values may be stored in config files in `app/config` (or `app/config/packages`) or inside the database. To avoid collisions, please use a namespace prefix. Example config key name:

    Config::store('cookies.minutesToBake', 5);

Application keys use the namespace "app".

## Database: Table And Attribute Names

If table names refer to models, e. g. "Game", prefer to pluralize and lowercase them: "games". Use underscores to seperate words: "delicious_cookies" Also use underscores to seperate words in attribute names.

> If a model belongs to a certain category (a news port belongs to a news category) the models / tables that are part of the default modules are named `<model>cats` (so the table for the news categories is named newscats).

## Database: Semantic Attribute Names

SQL does not offer an explicit way to give attributes a semantic meaning. Laravel and Contentify may give attributes a meaning depending on their names ("semantic names"):

* **id**: Entity id (*integer*, not null)
* **title**: Entity title (*string*, not null)
* **created_at**: Time of creation (*timestamp*)
* **updated_at**: Time of last update (*timestamp*)
* **deleted_at**: Time of deletion, when "soft delete" is enabled (*timestamp*)
* **creator_id**: User-ID of the entity creator (*integer*)
* **updater_id**: User-ID of the user doing the latest update (*integer*)
* **access_counter**: Counts accesses (*integer*)

## Session Variables

* **captchaCode**: Captcha code input
* **_message**: A flash message
* **redirect**: Redirect to this URL
* **recycleBinMode**: Display deleted entities?
* **_token**: CSRF token

## Code Style

Contentify tries to stay close to Laravel's code style. This is very much equivalent to [PSR coding standards](https://github.com/php-fig/fig-standards/tree/master/accepted). We encourage you to stick to these standards to ensure good code quality.
