_[Civic Documentation](../README.md) &#8594; [Civic Drupal Theme documentation](introduction.md)  &#8594; Namespaces_

# Namespaces

## Introduction

Component namespaces mirror the namespaces in Civic library as defined in
`civic.info.yml`.

Civic defines two sets of namespaces: atomic component namespaces and civic
atomic namespaces.

## Civic namespace definitions

```yml

components:
  namespaces:
    civic-base:
      - components/00-base
    base:
      - components/00-base
    civic-atoms:
      - components/01-atoms
    atoms:
      - components/01-atoms
    civic-molecules:
      - components/02-molecules
    molecules:
      - components/02-molecules
    civic-organisms:
      - components/03-organisms
    organisms:
      - components/03-organisms
    civic-templates:
      - components/04-templates
    templates:
      - components/04-templates
    civic-pages:
      - components/05-pages
    pages:
      - components/05-pages

```

## Sub-theme namespaces

Any sub-theme of Civic must implement the following component namespaces
(note how they are also contained in `civic.info.yml`). These namespaces
allow the overriding of Civic components.

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

The Civic namespaces (`civic-base`, `civic-atoms`, `civic-molecules` etc.) are
used to provide access to the unaltered Civic namespaces so the original civic
component can be extended and then overridden by a sub-theme.
