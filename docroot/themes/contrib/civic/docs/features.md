# Drupal Features

## Layout Builder

Civic provides two base layouts:
- Edge to edge layout
- Contained layout

Edge to edge provides landing page style layouts where the backgrounds of the component extends the full width of the
browser unless there is a sidebar whereupon the content becomes constrained.

Contained layout provides a constraint on width and is used in more news / event traditional style pages.

These layouts can be built upon and are defined in the `civic.info.yml` and the twig templates are stored in:
`civic-library/components/03-organisms/content` directory.

## Views

Civic provides a listing component as a paragraph type. This component provides the ability to create a configurable
view component within your civic page.

#### Civic listing paragraph

We have a special views component - Civic listing,

It provides configurations via paragraph to a view allowing content type restrictions, show / hide pagination,
altering the number of items and filter configuration options and has been built with being extended in mind.

This component utilised the `civic_listing` view for the block but this can be altered via `hook_civic_listing_view_name_alter`.
See [civic.api.php](../civic.api.php) for details.

In addition, your child theme can update the civic listing component and add your own filter options in or implement
ajax filtering for example.

On top of this civic provides large and basic filters out of the box to implement a stylised exposed view form inputs.

#### Views exposed form

For views with only 1 exposed filter, basic filtering (tag based) is enabled but as soon as there is more than one
exposed filter the large filter system (with dropdown filters) is enabled.


## Webform integration

Out of the box, civic integrates base webform form elements but it does not implement (or rather it is untested) the
more advanced composite components that the webform module can provide.
We welcome contributions extending this support.
