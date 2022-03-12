# Templates

Civic theme utilises the components that are defined within Civic component
library.

We access these components via **component namespaces**. Reading and understanding [namespaces](namespaces.md)
before reading the below.

## Using Civic library components

The idea being separating the Civic library component library and drupal templates is to create
a reusable set of components that can be used in multiple CMS systems.

The components stored within Civic library have had all their drupalism's removed so that they
can be cleanly implemented by wordpress (with plugin) or by any application
that can allow twig templates.

** Recommend reading [Components](../civic-library/docs/components.md) and understanding how
to create, extend, create custom components before reading how to connect these components with drupal. **

After setting up a component and structuring the twig file, you can include this new component in a drupal
template with an include statement. See the `civic/templates` directory for how Civic components have been included.

### Nuances with including component twig files.

Look closely at the `include` statement and be aware of the following difference:

```twig
# Provides all variables in the template to the included template.
{% include 'example.twig. %}

# Provides all variables in the current template and the specified variables.
{% include 'example.twig. with {
  theme: theme,
  'foo': 'bar',
} %}

# Provides only the specified variables to the included template
{% include 'example.twig. with {
  theme: theme,
  'foo': 'bar',
} only
%}

```

### Overriding Civic templates

For example, if you were wanting to utilise the new demo button (see `civic_starter_kit/components/01-atoms/demo-button`)
in your theme as a submit button you may override Civic submit button template `civic/templates/input--submit.html.twig`
changing the include in the template file to `@atoms/demo-button/demo-button.twig`.

If you need to provide custom variables to your component, you derive these variables through the preprocess hook system
Drupal provides. Look to the `civic/includes` directory for how the Civic components are preprocesed in Drupal.

### Preprocessing notes

Note, because we are not using drupalism's within our templates we equally should be aware that we have to be careful
not to rely on twig features only exist within Drupal. Link URLs and text need to be provided as data to the component
system, image url's need to be constructed (remembering to get the required image style url if implemented) and several
other nuances we need to be aware of when integrating with the component library.

### But, what about default field templates

There is a drawback (or advantage as it aligns more closely with mordern UX development workflows) to Civic in that the
architecture is based at the component level rather than then field level.

The theme has been implemented at the component level rather than field level which Drupal is based on which can
create some conflicts between the two systems. Outputting individual fields on the page will result in barebones
output as Civic theme is relying upon the developer to provide these field values to components rather than utilising
a field formatter.

Paragraphs are the primary mechanism for linking components up with Drupal and we have implemented a significant number
with Civic. These paragraphs can be created via paragraph fields within new content types and then organised within
layout builder to quickly create a new content type.
