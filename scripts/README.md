This directory contains the Salsa Hosting scaffold scripts and project scripts.

- `quant/` — scaffold-owned local-dev and provisioning scripts (build, provision,
  deploy, exec, post-provision hooks). Keep project-specific provisioning in
  `quant/post-provision.d/`.
- `custom/` — project build helpers (variant builds, host front-end builds).
- `ci/` — CI-only helpers (visual regression).
- Top-level `lint.*.sh`, `test.*.sh`, `update.*.sh`, `push.code.sh` — project
  lint/test/maintenance scripts invoked via ahoy or CI.
