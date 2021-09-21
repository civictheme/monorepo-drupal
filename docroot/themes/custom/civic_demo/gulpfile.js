const { src, dest, series, watch } = require('gulp');
const fs = require('fs')
const flatMap = require('gulp-flatmap');
const rename = require('gulp-rename')
const del = require('del')
const newer = require('gulp-newer');
const log = require('fancy-log')

// Component file globs.
const civicStorybookWatchDir = __dirname + '/../civic/components/**'
const civicChildStorybookWatchDir = __dirname + '/components/**'

// Theme names - `civic_demo` needs to be updated to civic child theme name.
// @todo Extract child theme name dynamically.
const baseThemeName = __dirname.replace('civic_demo', 'civic')

const childThemeName = __dirname

// Output directory of merged components.
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
         // log('original: ' + filePath)
         filePath = filePath
           .replace(__dirname, '')
           .replace(baseThemeName, childThemeName)
         log('replaced: ' + filePath)
         if (fs.existsSync(filePath)) {
           log('found: ' + filePath)
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
exports.default = series(cleanTask, buildTask)
exports.watch = series(watchTask)
