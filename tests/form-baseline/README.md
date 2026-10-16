# Form markup baselines

Normalised `<form>` markup snapshots of the form test pages, captured before
the form rendering refactor. These are a
development aid for diffing markup during the refactor, not a CI gate.

The normaliser strips Twig debug/component comments, form build ids and form
tokens, so captures are comparable across environments and page loads.

## Usage

Capture the committed baseline (run against the current branch's site):

```bash
node tests/form-baseline/capture.mjs
```

While refactoring, capture to a scratch directory and diff:

```bash
node tests/form-baseline/capture.mjs --out /tmp/form-after
diff -u tests/form-baseline/baseline/enquiry.html /tmp/form-after/enquiry.html
```

Override the site with `BASELINE_BASE_URL` (defaults to
`http://civictheme.docker.amazee.io`).
