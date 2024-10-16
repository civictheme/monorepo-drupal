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

function getStyleImports(paths, cwd) {
  return paths.map(path => getStyleImport(path, cwd)).join('\n')
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
  runBuild: false,
  runWatch: false,
  combineComponents: false,
  compileStyles: false,
  compileStylesEditor: false,
  compileStylesAdmin: false,
  compileStylesLayout: false,
  compileStylesVariables: false,
  compileJs: false,
  compileAssets: false,
}

const behaviourFlags = ['build', 'watch']
const flags = process.argv.slice(2)
const buildWatchFlagCount = flags.filter(f => behaviourFlags.indexOf(f) >= 0).length

if (buildWatchFlagCount <= 2) {
  config.runBuild = buildWatchFlagCount === 0 || flags.indexOf('build') >= 0
  config.runWatch = flags.indexOf('watch') >= 0
  config.combineComponents = true
  config.compileStyles = true
  config.compileStylesEditor = true
  config.compileStylesAdmin = false
  config.compileStylesLayout = false
  config.compileStylesVariables = true
  config.compileJs = true
  config.compileAssets = true
} else {
  // Fully configured - everything disabled by default.
  flags.forEach((flag) => {
    switch (flag) {
      case 'build':
        config.runBuild = true
        break
      case 'watch':
        config.runWatch = true
        break
      case 'combine':
        config.combineComponents = true
        break
      case 'styles':
        config.compileStyles = true
        break
      case 'styles-editor':
        config.compileStylesEditor = true
        break
      case 'styles-admin':
        config.compileStylesAdmin = true
        break
      case 'styles-layout':
        config.compileStylesLayout = true
        break
      case 'styles-variables':
        config.compileStylesVariables = true
        break
      case 'js':
        config.compileJs = true
        break
      case 'assets':
        config.compileAssets = true
        break
    }
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

const STYLE_FILE_IN           = `${DIR_COMPONENTS_OUT}/style.scss`
const STYLE_VARIABLE_FILE_IN  = `${DIR_COMPONENTS_OUT}/style.css_variables.scss`
const STYLE_EDITOR_FILE_IN    = `${DIR_ASSETS_IN}/sass/theme.editor.scss`
const STYLE_ADMIN_FILE_IN     = `${DIR_ASSETS_IN}/sass/theme.admin.scss`
const STYLE_LAYOUT_FILE_IN    = `${DIR_ASSETS_IN}/sass/theme.layout.scss`
const STYLE_FILE_OUT          = `${DIR_OUT}/styles.css`
const STYLE_EDITOR_FILE_OUT   = `${DIR_OUT}/styles.editor.css`
const STYLE_VARIABLE_FILE_OUT = `${DIR_OUT}/styles.variables.css`
const STYLE_ADMIN_FILE_OUT    = `${DIR_OUT}/styles.admin.css`
const STYLE_LAYOUT_FILE_OUT   = `${DIR_OUT}/styles.layout.css`

const VAR_CT_ASSETS_DIRECTORY = `$ct-assets-directory: '/themes/custom/${THEME_NAME}/dist/assets/';`

const JS_FILE_OUT             = `${DIR_OUT}/scripts.js`
const JS_CIVIC_IMPORTS        = `${DIR_COMPONENTS_OUT}/**/!(*.stories|*.component|*.min|*.test|*.script|*.utils).js`
const JS_LIB_IMPORTS          = [fullPath('./node_modules/@popperjs/core/dist/umd/popper.js')]
const JS_ASSET_IMPORTS        = [
  `${DIR_CIVICTHEME}/assets/js/**/*.js`,
  `${DIR_ASSETS_IN}/js/**/*.js`,
]

function build(options = {}) {
  const startTime = new Date().getTime()

  // --------------------------------------------------------------------------- CREATE DIR
  if (!fs.existsSync(DIR_OUT)) {
    fs.mkdirSync(DIR_OUT)
  }

  // --------------------------------------------------------------------------- GULP
  if (config.combineComponents) {
    runCommand(`rsync -a --delete ${DIR_UIKIT_COMPONENTS_IN}/ ${DIR_UIKIT_COPY_OUT}/`)
    runCommand(`rsync -a --delete ${DIR_UIKIT_COPY_OUT}/ ${DIR_COMPONENTS_OUT}/`)
    runCommand(`rsync -a ${DIR_COMPONENTS_IN}/ ${DIR_COMPONENTS_OUT}/`)
    console.log(`Saved: Combined folders`)
  }

  // --------------------------------------------------------------------------- STYLES
  if (config.compileStyles) {
    const rootDir = DIR_COMPONENTS_OUT
    const stylecss = [
      VAR_CT_ASSETS_DIRECTORY,
      loadStyle(STYLE_FILE_IN, rootDir),
      getStyleImports([
        'style.css_variables.scss',
        '../assets/sass/theme.scss',
      ], rootDir)
    ].join('\n')

    // Compile the styles.
    const compiled = sass.compileString(stylecss, { loadPaths: [rootDir] })
    fs.writeFileSync(STYLE_FILE_OUT, compiled.css, 'utf-8')
    console.log(`Saved: Component styles`)
  }

  if (config.compileStylesEditor) {
    const rootDir = PATH
    const editorcss = [
      VAR_CT_ASSETS_DIRECTORY,
      loadStyle(STYLE_EDITOR_FILE_IN, rootDir),
    ].join('\n')

    const compiled = sass.compileString(editorcss, { loadPaths: [rootDir] })
    fs.writeFileSync(STYLE_EDITOR_FILE_OUT, compiled.css, 'utf-8')
    console.log(`Saved: Editor styles`)
  }

  if (config.compileStylesAdmin) {
    const rootDir = PATH
    const compiled = sass.compile(STYLE_ADMIN_FILE_IN, { loadPaths: [rootDir] })
    fs.writeFileSync(STYLE_ADMIN_FILE_OUT, compiled.css, 'utf-8')
    console.log(`Saved: Admin styles`)
  }

  if (config.compileStylesLayout) {
    const rootDir = PATH
    const layoutcss = [
      VAR_CT_ASSETS_DIRECTORY,
      loadStyle(STYLE_LAYOUT_FILE_IN, rootDir),
    ].join('\n')

    const compiled = sass.compileString(layoutcss, { loadPaths: [rootDir] })
    fs.writeFileSync(STYLE_LAYOUT_FILE_OUT, compiled.css, 'utf-8')
    console.log(`Saved: Layout styles`)
  }

  if (config.compileStylesVariables) {
    const compiled = sass.compile(STYLE_VARIABLE_FILE_IN, { loadPaths: [DIR_COMPONENTS_OUT] })
    fs.writeFileSync(STYLE_VARIABLE_FILE_OUT, compiled.css, 'utf-8')
    console.log(`Saved: Style variables`)
  }

  // --------------------------------------------------------------------------- SCRIPTS
  if (config.compileJs) {
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
  if (config.compileAssets) {
    runCommand(`rsync -a --delete --exclude js --exclude sass ${DIR_ASSETS_IN} ${DIR_ASSETS_OUT}`)
  }

  // --------------------------------------------------------------------------- FINISH
  console.log(`Time taken: ${new Date().getTime() - startTime} ms`)
}

if (config.runBuild) {
  build()
}

if (config.runWatch) {
  console.log(`Watching: ${path.relative(PATH, DIR_COMPONENTS_IN)}`)
  const supportedExtensions = ['scss', 'js', 'twig']
  let lastModified = 0
  let timeout = null
  const watcher = fs.watch(DIR_COMPONENTS_IN, { recursive: true }, (event, filename) => {
    const ext = filename.split('.').pop()
    if (supportedExtensions.indexOf(ext) >= 0) {
      lastModified = new Date().getTime()
      waitToProcess()
    }
  })
  function waitToProcess() {
    const processStart = lastModified
    clearTimeout(timeout)
    timeout = setTimeout(() => {
      // If last modified has changed, wait again.
      (processStart !== lastModified) ? waitToProcess() : processWatch()
    }, 300)
  }
  function processWatch () {
    build()
  }
}
