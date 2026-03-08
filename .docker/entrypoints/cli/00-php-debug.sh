#!/bin/sh
set -e

if [ "${PHP_DEBUG}" = "1" ]; then
  echo "PHP_DEBUG enabled: turning on zend.assertions"
  echo "zend.assertions = 1" > /usr/local/etc/php/conf.d/zzz-debug.ini
else
  rm -f /usr/local/etc/php/conf.d/zzz-debug.ini
fi
