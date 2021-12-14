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
const civicStorybookWatchDir = `${__dirname}/../../contrib/civic/components/**`;
const civicChildStorybookWatchDir = `${__dirname}/components/**`;

// Theme names - `civic_starter_kit` needs to be updated to civic child theme name.
// @todo Extract child theme name dynamically.
const baseThemeName = __dirname.replace('contrib/civic_starter_kit', 'contrib/civic');

const childThemeName = __dirname;

// Output directory of merged components.
const outputDir = `${__dirname}/components-combined`;
const civicComponentsDir = `${__dirname}/.components-civic`;

// Add files to combined storybook.
function buildTask(cb) {
  let filePath;
  // Copy civic components to child theme to allow extending.
  src(civicStorybookWatchDir)
    .pipe(dest(civicComponentsDir));

  src(civicStorybookWatchDir)
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
  src(civicChildStorybookWatchDir)
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
  watch([civicStorybookWatchDir, civicChildStorybookWatchDir], {
    persistent: true,
    ignoreInitial: true,
  }, series(buildTask));

  cb();
}
exports.default = series(cleanTask, buildTask);
exports.watch = series(watchTask);
