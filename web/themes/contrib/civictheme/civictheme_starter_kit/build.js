// phpcs:ignoreFile
/*
Civictheme build - version 0.0.1 (alpha).

This is a simplified build system for civictheme, designed to:

- Speed up build time
- Improve auditability by reducing dependencies / external code

Build by calling:

  npm run build:new

Build and watch by calling:

  npm run build:watch:new

Notes:

- Requires Node version 22 (for the built in glob feature).
- No Windows support (unless through WSL).
- This relies on command line rsync.
- This does not support uglify or minifying JS / CSS code.
- Watch does not trigger on a directory change, only on a (scss, twig, js) file change.
*/

const fs = require('fs')
const path = require('path')
const { globSync } = require('node:fs')
const { execSync } = require('child_process')
const sass = require('sass')

// ----------------------------------------------------------------------------- UTILITIES

function runCommand(command) {
  execSync(command, (error, stdout, stderr) => {
    if (error) {
      console.log(`error: ${error.message}`)
      return
    }
    if (stderr) {
      console.log(`stderr: ${stderr}`)
      return
    }
    if (stdout) {
      console.log(stdout)
    }
  })
}

function getStyleImport(path, cwd) {
  return globSync(path, { cwd }).sort((a, b) => a.localeCompare(b)).map(i => `@import "${i}";`).join('\n')
}

function loadStyle(path, cwd) {
  const result = []
  let data = fs.readFileSync(path, 'utf-8')

  data.split('\n').forEach(line => {
    // Only glob imports with *!()| characters.
    const match = /@import '(.*[\*!()|].*)'/.exec(line)
    result.push(match ? getStyleImport(match[1], cwd) : line)
  })

  return result.join('\n')
}

function fullPath(filepath) {
  return path.resolve(PATH, filepath)
}

// ----------------------------------------------------------------------------- CONFIGURATION
const config = {
  build: false,
  watch: false,
  combine: false,
  styles: false,
  styles_editor: false,
  styles_admin: false,
  styles_layout: false,
  styles_variables: false,
  js: false,
  assets: false,
  base: false,
}

const flags = process.argv.slice(2)
const buildType = ['build', 'watch']
const buildWatchFlagCount = flags.filter(f => buildType.indexOf(f) >= 0).length

if (flags.length <= 2 && buildWatchFlagCount <= 2) {
  // If build and/or watch, or neither..
  config.build = buildWatchFlagCount === 0 || flags.indexOf('build') >= 0
  config.watch = flags.indexOf('watch') >= 0
  config.combine = true
  config.styles = true
  config.styles_editor = true
  config.styles_variables = true
  config.js = true
  config.assets = true
} else {
  // Fully configured from command line - everything disabled by default.
  flags.forEach((flag) => {
    config[flag] = true
  })
}

// ----------------------------------------------------------------------------- PATHS

const PATH = __dirname

const THEME_NAME              = PATH.split('/').reverse()[0]
const DIR_CIVICTHEME          = fullPath('../../contrib/civictheme/')
const DIR_COMPONENTS_IN       = fullPath('./components/')
const DIR_COMPONENTS_OUT      = fullPath('./components_combined/')
const DIR_UIKIT_COMPONENTS_IN = `${DIR_CIVICTHEME}/lib/uikit/components/`
const DIR_UIKIT_COPY_OUT      = fullPath('./.components-civictheme/')
const DIR_OUT                 = fullPath('./dist/')
const DIR_ASSETS_IN           = fullPath('./assets/')
const DIR_ASSETS_OUT          = fullPath('./dist/assets/')

const COMPONENT_DIR           = config.base ? DIR_COMPONENTS_IN : DIR_COMPONENTS_OUT
const STYLE_NAME              = config.base ? 'civictheme' : 'styles'
const SCRIPT_NAME             = config.base ? 'civictheme' : 'scripts'

const STYLE_FILE_IN           = `${COMPONENT_DIR}/style.scss`
const STYLE_VARIABLE_FILE_IN  = `${COMPONENT_DIR}/style.css_variables.scss`
const STYLE_THEME_FILE_IN     = `${DIR_ASSETS_IN}/sass/theme.scss`
const STYLE_EDITOR_FILE_IN    = `${DIR_ASSETS_IN}/sass/theme.editor.scss`
const STYLE_ADMIN_FILE_IN     = `${DIR_ASSETS_IN}/sass/theme.admin.scss`
const STYLE_LAYOUT_FILE_IN    = `${DIR_ASSETS_IN}/sass/theme.layout.scss`
const STYLE_FILE_OUT          = `${DIR_OUT}/${STYLE_NAME}.css`
const STYLE_EDITOR_FILE_OUT   = `${DIR_OUT}/${STYLE_NAME}.editor.css`
const STYLE_VARIABLE_FILE_OUT = `${DIR_OUT}/${STYLE_NAME}.variables.css`
const STYLE_ADMIN_FILE_OUT    = `${DIR_OUT}/${STYLE_NAME}.admin.css`
const STYLE_LAYOUT_FILE_OUT   = `${DIR_OUT}/${STYLE_NAME}.layout.css`

const VAR_CT_ASSETS_DIRECTORY = `$ct-assets-directory: '/themes/custom/${THEME_NAME}/dist/assets/';`

const JS_FILE_OUT             = `${DIR_OUT}/${SCRIPT_NAME}.js`
const JS_CIVIC_IMPORTS        = `${COMPONENT_DIR}/**/!(*.stories|*.component|*.min|*.test|*.script|*.utils).js`
const JS_LIB_IMPORTS          = [fullPath('./node_modules/@popperjs/core/dist/umd/popper.js')]
const JS_ASSET_IMPORTS        = [
  `${DIR_CIVICTHEME}/assets/js/**/*.js`,
  `${DIR_ASSETS_IN}/js/**/*.js`,
]

function build() {
  const startTime = new Date().getTime()

  // --------------------------------------------------------------------------- CREATE DIR
  if (!fs.existsSync(DIR_OUT)) {
    fs.mkdirSync(DIR_OUT)
  }

  // --------------------------------------------------------------------------- COMBINED FOLDER
  if (config.combine && !config.base) {
    runCommand(`rsync -a --delete ${DIR_UIKIT_COMPONENTS_IN}/ ${DIR_UIKIT_COPY_OUT}/`)
    runCommand(`rsync -a --delete ${DIR_UIKIT_COPY_OUT}/ ${DIR_COMPONENTS_OUT}/`)
    runCommand(`rsync -a ${DIR_COMPONENTS_IN}/ ${DIR_COMPONENTS_OUT}/`)
    console.log(`Saved: Combined folders`)
  }

  // --------------------------------------------------------------------------- STYLES
  if (config.styles) {
    const stylecss = [
      VAR_CT_ASSETS_DIRECTORY,
      loadStyle(STYLE_FILE_IN, COMPONENT_DIR),
      getStyleImport(STYLE_VARIABLE_FILE_IN, COMPONENT_DIR),
      getStyleImport(STYLE_THEME_FILE_IN, PATH),
      config.base ? [
        getStyleImport(STYLE_ADMIN_FILE_IN, PATH),
        loadStyle(STYLE_LAYOUT_FILE_IN, PATH),
      ].join('\n') : ''
    ].join('\n')

    const compiled = sass.compileString(stylecss, { loadPaths: [COMPONENT_DIR, PATH] })
    fs.writeFileSync(STYLE_FILE_OUT, compiled.css, 'utf-8')
    console.log(`Saved: Component styles`)
  }

  if (config.styles_editor) {
    const editorcss = [
      VAR_CT_ASSETS_DIRECTORY,
      loadStyle(STYLE_EDITOR_FILE_IN, PATH),
    ].join('\n')

    const compiled = sass.compileString(editorcss, { loadPaths: [PATH] })
    fs.writeFileSync(STYLE_EDITOR_FILE_OUT, compiled.css, 'utf-8')
    console.log(`Saved: Editor styles`)
  }

  if (config.styles_admin) {
    const compiled = sass.compile(STYLE_ADMIN_FILE_IN, { loadPaths: [PATH] })
    fs.writeFileSync(STYLE_ADMIN_FILE_OUT, compiled.css, 'utf-8')
    console.log(`Saved: Admin styles`)
  }

  if (config.styles_layout) {
    const layoutcss = [
      VAR_CT_ASSETS_DIRECTORY,
      loadStyle(STYLE_LAYOUT_FILE_IN, PATH),
    ].join('\n')

    const compiled = sass.compileString(layoutcss, { loadPaths: [PATH] })
    fs.writeFileSync(STYLE_LAYOUT_FILE_OUT, compiled.css, 'utf-8')
    console.log(`Saved: Layout styles`)
  }

  if (config.styles_variables) {
    const compiled = sass.compile(STYLE_VARIABLE_FILE_IN, { loadPaths: [COMPONENT_DIR] })
    fs.writeFileSync(STYLE_VARIABLE_FILE_OUT, compiled.css, 'utf-8')
    console.log(`Saved: Variable styles`)
  }

  // --------------------------------------------------------------------------- SCRIPTS
  if (config.js) {
    const jsOutData = []

    // Third party imports.
    JS_LIB_IMPORTS.forEach(filename => {
      jsOutData.push(fs.readFileSync(filename, 'utf-8'))
    })

    // Civictheme asset imports.
    globSync(JS_ASSET_IMPORTS).forEach(filename => {
      jsOutData.push(fs.readFileSync(filename, 'utf-8'))
    })

    // Civictheme component imports.
    globSync(JS_CIVIC_IMPORTS).forEach(filename => {
      const name = `${THEME_NAME}_${filename.split('/').reverse()[0].replace('.js', '').replace(/-/g, '_')}`
      const body = fs.readFileSync(filename, 'utf-8')
      const outBody = `Drupal.behaviors.${name} = {attach: function (context, settings) {\n${body}\n}};`
      jsOutData.push(outBody)
    })

    fs.writeFileSync(JS_FILE_OUT, jsOutData.join('\n'), 'utf-8')
    console.log(`Saved: Compiled javascript`)
  }

  // --------------------------------------------------------------------------- ASSETS
  if (config.assets) {
    runCommand(`rsync -a --delete --exclude js --exclude sass ${DIR_ASSETS_IN}/ ${DIR_ASSETS_OUT}/`)
  }

  // --------------------------------------------------------------------------- FINISH
  console.log(`Time taken: ${new Date().getTime() - startTime} ms`)
}

if (config.build) {
  build()
}

if (config.watch) {
  console.log(`Watching: ${path.relative(PATH, DIR_COMPONENTS_IN)}/`)
  const supportedExtensions = ['scss', 'js', 'twig']
  let timeout = null
  const watcher = fs.watch(DIR_COMPONENTS_IN, { recursive: true }, (event, filename) => {
    const ext = filename.split('.').pop()
    if (supportedExtensions.indexOf(ext) >= 0) {
      clearTimeout(timeout)
      timeout = setTimeout(build, 300)
    }
  })
}
