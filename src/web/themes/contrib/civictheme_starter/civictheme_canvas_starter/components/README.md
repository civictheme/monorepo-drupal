# Canvas component overrides

One directory per override. Each `*.component.yml` declares
`replaces: 'civictheme_starter:<component>'` and a `props` schema (core
refuses a replacement unless both components declare one); the post-processor
rewrites the target to the generated main theme. See the kit README for the
mechanics.
