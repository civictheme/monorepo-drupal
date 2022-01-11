echo "  > Installing civic_demo theme."
$drush ${DRUSH_ALIAS} theme:enable civic_demo -y

echo "  > Making civic_demo a default theme."
$drush ${DRUSH_ALIAS} config-set system.theme default civic_demo -y

echo "  > Updating civic_demo theme settings."
$drush ${DRUSH_ALIAS} ev "module_load_include('inc', 'cd_core', 'cd_core.civic_demo'); cd_core_civic_demo_update_theme_settings();"

if [ "$SKIP_SUBTHEME_FE" != "1" ] && command -v npm &> /dev/null; then
  pushd $APP/docroot/themes/custom/civic_demo >/dev/null || exit 1

  echo "  > Installing FE dependencies."
  npm ci

  echo "  > Running FE build."
  npm run build

  popd >/dev/null || exit 1
fi
