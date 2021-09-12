const { src, dest, series, watch } = require('gulp');
const fs = require('fs')
const flatMap = require('gulp-flatmap');
const rename = require('gulp-rename')
const del = require('del')
const newer = require('gulp-newer');
const log = require('fancy-log')

// Component file globs.
const civicStorybookWatchDir = __dirname + '/../civic_emulsify/components/**'
const civicChildStorybookWatchDir = __dirname + '/components/**'

// Theme names.
const baseThemeName = 'civic_emulsify'
const childThemeName = 'civic_demo_emulsify'

// Output directory of merged storybook.
const outputDir = __dirname + '/.storybook-components'

// Periodic rebuild of combined storybook.
let periodicCleanup = function cleanUp() {
  log('Running Periodic rebuild of storybook')
  cleanTask(() => {})
  buildTask(() => {log('Completed Periodic rebuild.')})
};

// Add files to combined storybook.
function buildTask(cb) {
  let filePath
  src(civicStorybookWatchDir)
    .pipe(flatMap((stream , file) => {
       filePath = file.path
       if(filePath !== undefined) {
         filePath = filePath.replace(baseThemeName, childThemeName);
         if (fs.existsSync(filePath)) {
           return src(filePath)
             .pipe(rename(function (path) {

               path.dirname = filePath
                 .replace(__dirname + '/components', '')
                 .replace(path.basename + path.extname, '')

               return path
             }))
         }
       }

       return stream
    }))
    .pipe(newer(outputDir))
    .pipe(dest(outputDir));

  // Sync the child theme components.
  src(civicChildStorybookWatchDir)
    .pipe(newer(outputDir))
    .pipe(dest(outputDir));

  cb()
}

// Rebuild storybook to clear out deleted items.
async function cleanTask(cb) {
  await del(outputDir + '/**')
  cb()
}

// Sets a periodic rebuild fixes bugs with file watcher.
async function periodicCleanTask(cb) {
  setTimeout(periodicCleanup, 1000 * 120)
}

// Watch files for changes.
function watchTask(cb) {
  watch([civicStorybookWatchDir, civicChildStorybookWatchDir], {
    persistent: true,
    ignoreInitial: true
  }, series(buildTask));

  cb()
}
exports.build = series(cleanTask, buildTask)
exports.default = series(cleanTask, buildTask)
exports.watch = series(watchTask)
