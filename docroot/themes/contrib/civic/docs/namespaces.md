# Namespaces

## Introduction

Component namespaces mirror the namespaces in civic library as defined in `civic.info.yml`.

Civic defines two sets of namespaces, it defines atomic component namespaces
and civic atomic namespaces.

### Important namespace information

The civic namespaces (`civic-base`, `civic-atoms`, `civic-molecules` etc) are used to provide access to the
unaltered civic namespaces so the original civic component can be extended and then overridden
by a child theme.


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

## Child theme namespaces

Any child theme of civic must implement the following component namespaces
(note how they are also contained in civic.info.yml). These namespaces
allow the overriding of civic components.

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
