_[CivicTheme Documentation](../README.md) &#8594; [CivicTheme Drupal Theme documentation](README.md)  &#8594; Namespaces_

# Namespaces

## Introduction

Component namespaces mirror the namespaces in CivicTheme library as defined in
`civictheme.info.yml`.

CivicTheme defines two sets of namespaces: atomic component namespaces and civictheme
atomic namespaces.

## CivicTheme namespace definitions

```yml

components:
  namespaces:
    civictheme-base:
      - components/00-base
    base:
      - components/00-base
    civictheme-atoms:
      - components/01-atoms
    atoms:
      - components/01-atoms
    civictheme-molecules:
      - components/02-molecules
    molecules:
      - components/02-molecules
    civictheme-organisms:
      - components/03-organisms
    organisms:
      - components/03-organisms
    civictheme-templates:
      - components/04-templates
    templates:
      - components/04-templates
    civictheme-pages:
      - components/05-pages
    pages:
      - components/05-pages

```

## Sub-theme namespaces

Any sub-theme of CivicTheme must implement the following component namespaces
(note how they are also contained in `civictheme.info.yml`). These namespaces
allow the overriding of CivicTheme components.

```yaml

components:
  namespaces:
    base:
      - components/00-base
    atoms:
      - components/01-atoms
    molecules:
      - components/02-molecules
    organisms:
      - components/03-organisms
    templates:
      - components/04-templates
    pages:
      - components/05-pages

```

The CivicTheme namespaces (`civictheme-base`, `civictheme-atoms`, `civictheme-molecules` etc.) are
used to provide access to the unaltered CivicTheme namespaces so the original civictheme
component can be extended and then overridden by a sub-theme.
