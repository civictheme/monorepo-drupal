# Accessibility (axe-core) scans

Scans the CivicTheme form pages with [axe-core](https://github.com/dequelabs/axe-core)
driven by `puppeteer-core` against a system Chrome/Chromium — the same
browser setup the visual regression CI job uses. Part of the form rendering
refactor baseline.

## Run locally

```bash
ahoy test-a11y
```

This starts the `chromium` sidecar container (Chromium + Node, local/CI only
— `quant.type: none` / `lagoon.type: none`, never deployed) and runs the
suite inside it against `http://nginx:8080`.

To run against a host browser instead:

```bash
cd tests/a11y
npm install
npm test
```

Defaults to `http://civictheme.docker.amazee.io`. Override with:

```bash
A11Y_BASE_URL=http://nginx:8080 npm test
A11Y_SCENARIO=enquiry-error-state npm test
```

If no Chrome/Chromium is found automatically, set `PUPPETEER_EXECUTABLE_PATH`.

## What it scans

Scenarios live in `a11y.config.json`. Each scans the `main` region of a page
for WCAG 2.0/2.1/2.2 A and AA violations. Scenarios with `actions` first
interact with the page (e.g. submit the enquiry form empty to scan the
validation error state, or advance the feedback wizard to step 2).

## Failure policy

The run exits non-zero when any violation with `critical` or `serious` impact
is found (configurable via `A11Y_FAIL_IMPACTS`), or when a scan errors.
`moderate`/`minor` violations are reported as warnings. The full report is
written to `.logs/a11y/report.json`.

## CI wiring

Use the `chromium` compose service (it is part of the stack in CI):

```bash
docker compose up -d chromium
docker compose exec -T -w /app/tests/a11y chromium npm ci
docker compose exec -T -w /app/tests/a11y -e A11Y_BASE_URL=http://nginx:8080 chromium npm test
```

The visual-regression job could use the same container instead of installing
Chromium into `cli` on every run.
