#!/usr/bin/env bash
# Salsa scaffold — interactive y/N confirmation prompt.
#
# Exits 0 on an affirmative answer, 1 otherwise. Used by the destructive ahoy
# commands (down, clean) so their bodies don't recurse through `ahoy confirm`
# (see exec.sh for why nested `ahoy` calls are avoided).
#
# Usage:
#   scripts/quant/confirm.sh "Delete everything?" && rm -rf ...

# CivicTheme adaptation: honour AHOY_CONFIRM_RESPONSE so CI and scripts can
# pre-answer the prompt, the same way the DrevOps stack did
# (AHOY_CONFIRM_RESPONSE=y ahoy build-minimal).
if [ -n "${AHOY_CONFIRM_RESPONSE:-}" ]; then
  echo "${1:-Are you sure?} [y/N] ${AHOY_CONFIRM_RESPONSE}"
  case "$AHOY_CONFIRM_RESPONSE" in
    y|Y|yes|YES|true) exit 0 ;;
    *) exit 1 ;;
  esac
fi

read -r -p "${1:-Are you sure?} [y/N] " ans
case "$ans" in
  y|Y|yes|YES) exit 0 ;;
  *) exit 1 ;;
esac
