#!/usr/bin/env bash
# shellcheck disable=SC2181,SC2016,SC2002
##
# Check spelling.
#

set -e
[ -n "${DREVOPS_DEBUG}" ] && set -x

command -v aspell > /dev/null || ( echo "ERROR: aspell command is not available." && exit 1 )

CUR_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

targets=()
while IFS=  read -r -d $'\0'; do
    targets+=("$REPLY")
done < <(
  find \
    "${CUR_DIR}"/docroot/themes/contrib/civictheme/docs \
    "${CUR_DIR}"/docroot/themes/contrib/civictheme/civictheme_library/docs \
    -type f \
    \( -name "*.md" \) \
    -not -path "*vendor*" \
    -print0
  )

targets+=(CI.md)
targets+=(DEPLOYMENT.md)
targets+=(DEVELOPMENT.md)
targets+=(FAQs.md)
targets+=(README.md)
targets+=(RELEASING.md)
targets+=(TESTING.md)

echo "==> Start checking spelling."
for file in "${targets[@]}"; do
  if [ -f "${file}" ]; then
    echo "Checking file ${file}"

    cat "${file}" | \
    # Remove { } attributes.
    sed -E 's/\{:([^\}]+)\}//g' | \
    # Remove HTML.
    sed -E 's/<([^<]+)>//g' | \
    # Remove code blocks.
    sed  -n '/\`\`\`/,/\`\`\`/ !p' | \
    # Remove inline code.
    sed  -n '/\`/,/\`/ !p' | \
    # Remove anchors.
    sed -E 's/\[.+\]\([^\)]+\)//g' | \
    # Remove links.
    sed -E 's/http(s)?:\/\/([^ ]+)//g' | \
    aspell --lang=en --encoding=utf-8 --personal="${CUR_DIR}/scripts/.aspell.en.pws" list | tee /dev/stderr | [ "$(wc -l)" -eq 0 ]

    if  [ "$?" -ne 0 ]; then
      exit 1
    fi
  fi
done;
