# FAQs

## How to know which commands are available?

```bash
ahoy help
```

## How to pass CLI arguments to commands?

```bash
ahoy mycommand -- myarg1 myarg2 --myoption1 --myoption2=myvalue
```

## How to clear Drupal cache?

```bash
ahoy drush cr
```

## How to login to a Drupal site?

```bash
ahoy login
```

## How to connect to the database?

If you have [Sequel Ace](https://sequel-ace.com/):

```bash
ahoy db
```

Otherwise:

1. Run `ahoy info` and grab the DB host port number.
2. Use these connection details:

- Host: `127.0.0.1`
- Username: `drupal`
- Password: `drupal`
- Database: `drupal`
- Port: the port from step 1

## How to use Xdebug?

1. Run `ahoy debug`.
2. Enable listening for incoming debug connections in your IDE.
3. If required, provide server URL to your IDE as it appears in the browser.
4. Enable Xdebug flag in the request coming from your web browser (use one of
   the extensions or add `?XDEBUG_SESSION_START=1` to your URL).
5. Set a breakpoint in your IDE and perform a request in the web browser.

Use the same commands to debug CLI scripts.

To disable, run `ahoy up`.

## How to use Xdebug on Behat scripts?

1. Enable debugging: `ahoy debug`
2. Enter CLI container: `ahoy cli`
3. Run Behat tests:

```bash
vendor/bin/behat path/to/test.feature
```

## What should I do to switch to a "clean" branch environment?

Provided that your stack is already running:

1. Switch to your branch
2. `composer install`
3. `ahoy site-install`

Note that you do not need to rebuild the full stack using `ahoy build` every
time.
However, sometimes you would want to have an absolutely clean environment - in
that case, use `ahoy build`.

## How to just import the database?

Provided that your stack is already running:

```bash
ahoy drush sql-drop -y;
ahoy drush sql-cli < .data/db.sql
```

## How to add Drupal modules

```bash
composer require drupal/module_name
```

## How to add patches for Drupal modules

1. Add `title` to patch on https://drupal.org to the `patches` array in `extra`
   section in `composer.json`.

```json
"extra": {
    "patches": {
        "drupal/core": {
            "Contextual links should not be added inside another link": "https://www.drupal.org/files/issues/contextual_links_should-2898875-3.patch"
        }
    }
}
```

2. Run

```bash
composer update --lock`
```
