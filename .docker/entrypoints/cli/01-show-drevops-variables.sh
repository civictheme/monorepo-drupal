#!/bin/sh
set -e

# Post-rollout task: Show DrevOps variables.
# Generated from .lagoon.yml

env -0  | sort -z | tr '\0' '\n' | grep ^DREVOPS_ || true
