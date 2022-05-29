const {
  src,
  dest,
  series,
  watch,
} = require('gulp');
const fs = require('fs');
const flatMap = require('gulp-flatmap');
const rename = require('gulp-rename');
const del = require('del');
const newer = require('gulp-newer');

// Component file globs.
// @codingStandardsIgnoreLine
const civicthemeStorybookWatchDir = `${__dirname}/../../contrib/civictheme/components/**`;
const civicthemeChildStorybookWatchDir = `${__dirname}/components/**`;

// Theme names - `civictheme_starter_kit` needs to be updated to civictheme child theme name.
// @todo Extract child theme name dynamically.
const baseThemeName = __dirname.replace('custom/civictheme_starter_kit', 'contrib/civictheme');

const childThemeName = __dirname;

// Output directory of merged components.
const outputDir = `${__dirname}/components_combined`;
const civicthemeComponentsDir = `${__dirname}/.components-civictheme`;

// Add files to combined storybook.
function buildTask(cb) {
  let filePath;
  // Copy civictheme components to child theme to allow extending.
  src(civicthemeStorybookWatchDir)
    .pipe(dest(civicthemeComponentsDir));

  src(civicthemeStorybookWatchDir)
    .pipe(flatMap((stream, file) => {
      filePath = file.path;
      if (filePath !== undefined) {
        filePath = filePath
          .replace(__dirname, '')
          .replace(baseThemeName, childThemeName);
        if (fs.existsSync(filePath)) {
          return src(filePath)
            .pipe(rename((path) => {
              path.dirname = filePath
                .replace(`${__dirname}/components`, '')
                .replace(path.basename + path.extname, '');

              return path;
            }));
        }
      }

      return stream;
    }))
    .pipe(newer(outputDir))
    .pipe(dest(outputDir));

  // Sync the child theme components.
  src(civicthemeChildStorybookWatchDir)
    .pipe(newer(outputDir))
    .pipe(dest(outputDir));

  cb();
}

// Rebuild storybook to clear out deleted items.
async function cleanTask(cb) {
  await del(`${outputDir}/**`);
  cb();
}

// Watch files for changes.
function watchTask(cb) {
  watch([civicthemeStorybookWatchDir, civicthemeChildStorybookWatchDir], {
    persistent: true,
    ignoreInitial: true,
  }, series(buildTask));

  cb();
}
exports.default = series(cleanTask, buildTask);
exports.watch = series(watchTask);
