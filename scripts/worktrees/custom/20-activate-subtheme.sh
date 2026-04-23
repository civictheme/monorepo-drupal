#!/usr/bin/env bash
#
# Run civictheme's provision-20-activate-subtheme.sh inside the worktree's
# cli container so the civictheme_demo subtheme is created, enabled, and its
# FE assets are built. Without this, fast-up'd worktrees land on a site
# without the demo subtheme.
#
# Invoked automatically by scripts/worktrees/worktree.sh at the end of
# `ahoy worktree build` / `ahoy worktree fast-up` — see ./README.md.

set -euo pipefail

echo "[custom-hook] activating civictheme_demo subtheme in ${WORKTREE_PROJECT}"
ahoy cli ./scripts/custom/provision-20-activate-subtheme.sh
