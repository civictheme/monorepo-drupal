# Custom worktree hooks

Every `*.sh` file in this directory is executed **after** a worktree is brought
up via `ahoy worktree build` or `ahoy worktree fast-up`. Scripts run in
lexicographic order (prefix with `00-`, `10-`, `99-` to control ordering).

Use this for any per-worktree setup a base Drupal project doesn't cover — e.g.
seeding test users, enabling dev modules, importing fixture content, or
pinging an internal tool.

## Conventions

- File name ends in `.sh`.
- Script must be executable (`chmod +x`). The worktree tooling will make it
  executable if it isn't, as a fallback.
- A non-zero exit logs a warning but does NOT abort the worktree setup.

## Environment provided to each script

| Variable              | Example                                                  |
|-----------------------|----------------------------------------------------------|
| `WORKTREE_PATH`       | `/home/you/work/my-project-worktrees/feature-foo`        |
| `WORKTREE_BRANCH`     | `feature/foo` (as the user typed it)                     |
| `WORKTREE_SAFE_NAME`  | `feature-foo` (sanitized — used in dir + tag names)      |
| `WORKTREE_PROJECT`    | `my-project--feature-foo` (COMPOSE_PROJECT_NAME)         |
| `WORKTREE_URL`        | `my-project--feature-foo.docker.amazee.io`               |

The script runs with `cwd = $WORKTREE_PATH`, so `ahoy` / `docker compose`
commands address the worktree's stack by default.
