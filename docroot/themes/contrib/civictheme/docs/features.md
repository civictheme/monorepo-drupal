# Drupal Features

## Layout Builder

CivicTheme provides two base layouts:
- Edge-to-edge layout
- Contained layout

`Edge-to-edge` layout provides landing page style where the backgrounds of the
component extends the full width of the browser window. If there is a sidebar
- the content becomes constrained automatically.

`Contained` layout provides a constraint on container width and is used in more
traditional style pages.

These layouts can be built upon and are defined in the `civictheme.info.yml` and the
twig templates are located in `../civictheme_library/components/03-organisms/content`
directory.

## Views

CivicTheme provides Views integration.

### CivicTheme Automated list component

Listing component allows editors to build lists of contents and place them
anywhere on the page as a component.

This is achieved by providing a `civictheme_automated_list` paragraph with field values
passed to a preprocessing function that is mapped to a pre-configured
`civictheme_automated_list` view.

The view provides configurations via paragraph to a view allowing content type
restrictions, show / hide pagination, altering the number of items and filter
configuration options.

It is possible to replace the default `civictheme_automated_list` view with a more custom
one required for a specific site via `hook_civictheme_automated_list_view_name_alter()` (see
[civictheme.api.php](../civictheme.api.php) for details).

CivicTheme also provides support for filters in an exposed form. For views with only
1 exposed filter, `Basic Filter` component (tag based) is enabled,
but as soon as there is more than one exposed filter - the Large Filter
component (with dropdown filters) is enabled automatically.

## Webform integration

CivicTheme provides integration with basic webform form elements.

More advanced composite components are not supported yet and we welcome
contributions extending this support.
