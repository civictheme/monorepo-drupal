// Something to use when events are received.
const chokidar = require('chokidar');
const glob = require("glob")
const civicStorybookWatchDir = __dirname + '/../../civic_emulsify/components/**/*.*'
const civicChildStorybookWatchDir = __dirname + '/../components/**/*.*'
const themeName = 'civic_demo_emulsify'

const outputDir = __dirname + '/../.storybook-components/'

var gs = require('glob-stream');

var readable = gs('./files/**/*.coffee', { /* options */ });

var writable = /* your WriteableStream */

readable.pipe(writable);

const watcher = chokidar.watch(civicStorybookWatchDir, {
  persistent: true,
  ignoreInitial: true
});
// Add event listeners.
watcher
  .on('add', (path, stat) => {
    console.log('add', path)
  })
  .on('change', (path, stat) => {
    console.log('change', path)
  })
  .on('unlink', (path, stat) => {
    console.log('delete', path)
  })
