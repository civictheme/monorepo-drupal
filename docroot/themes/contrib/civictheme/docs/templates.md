# Templates

CivicTheme theme utilises the components that are defined within CivicTheme component
library.

We access these components via [component namespaces](namespaces.md).

## Using CivicTheme library components

The idea being separating the CivicTheme library component library and Drupal templates is to create
a reusable set of components that can be used in multiple CMS systems.

The components stored within CivicTheme library have had all their Drupalisms
removed so that they can be cleanly used by other CMSs.

** Read more about [Components](../civictheme-library/docs/components.md) to
understand how to create or extend components before reading how to connect
these components with Drupal. **

After setting up a component and structuring the twig file, you can include this
new component in a Drupal template with an `include` statement. See the
`civictheme/templates` directory for how CivicTheme components have been included.

### Overriding CivicTheme templates

For example, if you were wanting to utilise the new demo button
(see `civictheme_starter_kit/components/01-atoms/demo-button`) in your theme as a
submit button you, may override CivicTheme submit button template
`civictheme/templates/input--submit.html.twig` changing the include in the template
file to `@atoms/demo-button/demo-button.twig`.

If you need to provide custom variables to your component, you derive these
variables through the preprocess hook system Drupal provides. Look at the files
in `civictheme/includes` directory for how the CivicTheme components are preprocesed in
Drupal.

Because we are not using Drupalisms within our templates we equally
should be aware that we have to be careful not to rely on Twig features that
only exist within Drupal. Link URLs and text need to be provided as data to
the component system, image URLs need to be constructed (remembering to get
the required image style URL if implemented) and several other nuances we need
to be aware of when integrating with the component library.

### But, what about default field templates

There is a drawback (or advantage as it aligns more closely with modern UX
development workflows) to CivicTheme in that the architecture is based at the
component level rather than then field level.

Outputting individual fields on the page will result in bare bones output as
CivicTheme theme is relying upon the developer to provide these field values to
components rather than utilising a field formatter.

Paragraphs are the primary mechanism for linking components up with Drupal, and
we have implemented a significant number with CivicTheme. These paragraphs can be
created via paragraph fields within new content types and then organised within
layout builder to quickly create a new content type.
