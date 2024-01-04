// phpcs:ignoreFile
/**
 * @file
 * Additional processing to merge current and CivicTheme components.
 *
 * Running this script produces 2 directories:
 *  - components_combined - a directory of merged CivicTheme components with
 *    overrides from the current theme's components used to collect the "full"
 *    set of components for a build.
 *  - .components-civictheme - a copy of CivicTheme components used by Webpack
 *    to resolve inclusions of "clean" CivicTheme components when extending
 *    CivicTheme components in this theme.
 */

const {src, dest, series, watch} = require('gulp');
const del = require('del');
const newer = require('gulp-newer');

// Directory with current theme components.
const srcComponentsCurrentDir = `${__dirname}/components`;
// Directory with CivicTheme components.
const srcComponentsCivicthemeDir = __dirname.includes('starter_kit') ? `${__dirname}/../lib/uikit/components` : `${__dirname}/../../contrib/civictheme/lib/uikit/components`;

// Output directory of merged components.
const dstComponentsCombinedDir = `${__dirname}/components_combined`;
// Output directory of CivicTheme components.
const dstComponentsCivicthemeDir = `${__dirname}/.components-civictheme`;

/**
 * A task to build assets.
 */
function buildTask(cb) {
  // Copy components from CivicTheme into a dedicated directory within current
  // theme.
  src(`${srcComponentsCivicthemeDir}/**`)
    .pipe(dest(dstComponentsCivicthemeDir));

  // Merge components from CivicTheme and current theme into a dedicated
  // directory within current theme.
  src([`${srcComponentsCivicthemeDir}/**`, `${srcComponentsCurrentDir}/**`])
    .pipe(newer(dstComponentsCombinedDir))
    .pipe(dest(dstComponentsCombinedDir));

  cb();
}

/**
 * A task to clean output directory.
 */
async function cleanTask(cb) {
  await del(`${dstComponentsCivicthemeDir}/**`);
  await del(`${dstComponentsCombinedDir}/**`);
  cb();
}

/**
 * A task to watch for changes.
 */
function watchTask(cb) {
  watch([`${srcComponentsCivicthemeDir}/**`, `${srcComponentsCurrentDir}/**`], {
    persistent: true,
    ignoreInitial: true,
  }, series(buildTask));

  cb();
}

exports.default = series(cleanTask, buildTask);
exports.watch = series(watchTask);
