// phpcs:ignoreFile
/*
Civictheme build - version 0.0.1 (alpha).

This is a simplified build system for civictheme, designed to:

- Speed up build time
- Improve auditability by reducing dependencies / external code

Notes:

- No Windows support (unless through WSL).
- This relies on command line rsync.
- This does not support uglify or minifying JS / CSS code.
- Watch does not trigger on a directory change, only on a (scss, twig, js) file change.
*/

import fs from 'fs'
import path from 'path'
import { globSync } from 'glob'
import { execSync, spawn } from 'child_process'
import * as sass from 'sass-embedded'

// ----------------------------------------------------------------------------- CONFIG AND START

const config = {
  build: false,
  watch: false,
  cli: false,
  lintex: false,
  combine: false,
  styles: false,
  styles_storybook: false,
  styles_editor: false,
  styles_admin: false,
  styles_layout: false,
  styles_variables: false,
  styles_stories: false,
  styles_theme: false,
  js_drupal: false,
  js_storybook: false,
  sdc_base: false,
  sdc_components: false,
  assets: false,
  constants: false,
  base: false,
}

const flags = process.argv.slice(2)
if (['cli', 'lintex'].indexOf(flags[0]) >= 0) {
  config[flags[0]] = true
} else {
  const buildType = ['build', 'watch']
  const buildWatchFlagCount = flags?.filter(f => buildType.indexOf(f) >= 0).length

  if (flags.length <= 2 && buildWatchFlagCount <= 2) {
    // If build and/or watch, or neither..
    config.build = buildWatchFlagCount === 0 || flags.indexOf('build') >= 0
    config.watch = flags.indexOf('watch') >= 0
    config.combine = true
    config.styles_storybook = true
    config.styles_editor = true
    config.styles_variables = true
    config.styles_stories = true
    config.styles_theme = true
    config.sdc_base = true
    config.sdc_components = true
    config.assets = true
    config.constants = true
  } else {
    // Fully configured from command line - everything disabled by default.
    flags.forEach((flag) => {
      config[flag] = true
    })
  }
}

let startTime = new Date().getTime()
let lastTime = null

const PATH = import.meta.dirname

const THEME_NAME                = PATH.split('/').reverse()[0]
const BUILD_CONFIG_DIR          = fullPath('./build-config.json')
const DIR_COMPONENTS_IN         = fullPath('./components/')
const DIR_OUT                   = fullPath('./dist/')
const DIR_ASSETS_IN             = fullPath('./assets/')
const DIR_ASSETS_OUT            = fullPath('./dist/assets/')

const DIR_CIVICTHEME            = config.base ? null : getCivicthemeDir(PATH, 'themes', '/**/civictheme')
const DIR_UIKIT_COMPONENTS_IN   = config.base ? null : `${DIR_CIVICTHEME}/components/`
const DIR_UIKIT_COPY_OUT        = config.base ? null : fullPath('./.components-civictheme/')
const DIR_COMPONENTS_OUT        = config.base ? null : fullPath('./components_combined/')

const COMPONENT_DIR             = config.base ? DIR_COMPONENTS_IN : DIR_COMPONENTS_OUT
const STYLE_NAME                = config.base ? 'civictheme' : 'styles'
const SCRIPT_NAME               = config.base ? 'civictheme' : 'scripts'
const DRUPAL_THEME_FOLDER       = config.base ? 'contrib' : 'custom'

const STYLE_FILE_IN             = `${COMPONENT_DIR}/style.scss`
const STYLE_VARIABLE_FILE_IN    = `${COMPONENT_DIR}/style.css_variables.scss`
const STYLE_STORIES_FILE_IN     = `${COMPONENT_DIR}/style.stories.scss`
const STYLE_THEME_FILE_IN       = `${DIR_ASSETS_IN}/sass/theme.scss`
const STYLE_EDITOR_FILE_IN      = `${DIR_ASSETS_IN}/sass/theme.editor.scss`
const STYLE_ADMIN_FILE_IN       = `${DIR_ASSETS_IN}/sass/theme.admin.scss`
const STYLE_LAYOUT_FILE_IN      = `${DIR_ASSETS_IN}/sass/theme.layout.scss`
const STYLE_FILE_OUT            = `${DIR_OUT}/${STYLE_NAME}.css`
const STYLE_STORYBOOK_FILE_OUT  = `${DIR_OUT}/${STYLE_NAME}.storybook.css`
const STYLE_EDITOR_FILE_OUT     = `${DIR_OUT}/${STYLE_NAME}.editor.css`
const STYLE_VARIABLE_FILE_OUT   = `${DIR_OUT}/${STYLE_NAME}.variables.css`
const STYLE_ADMIN_FILE_OUT      = `${DIR_OUT}/${STYLE_NAME}.admin.css`
const STYLE_LAYOUT_FILE_OUT     = `${DIR_OUT}/${STYLE_NAME}.layout.css`
const STYLE_STORIES_FILE_OUT    = `${DIR_OUT}/${STYLE_NAME}.stories.css`
const STYLE_THEME_FILE_OUT      = `${DIR_OUT}/${STYLE_NAME}.theme.css`

const DIR_CT_ASSETS             = `/themes/${DRUPAL_THEME_FOLDER}/${THEME_NAME}/dist/assets/`
const DIR_SB_ASSETS             = `/assets/`
const VAR_CT_ASSETS_DIRECTORY   = `$ct-assets-directory: '${DIR_CT_ASSETS}';`
const VAR_SB_ASSETS_DIRECTORY   = `$ct-assets-directory: '${DIR_SB_ASSETS}';`

const JS_FILE_OUT               = `${DIR_OUT}/${SCRIPT_NAME}.js`
const JS_STORYBOOK_FILE_OUT     = `${DIR_OUT}/${SCRIPT_NAME}.storybook.js`
const JS_CIVIC_IMPORTS          = `${COMPONENT_DIR}/**/!(*.stories|*.stories.data|*.component|*.min|*.test|*.script|*.utils).js`
const JS_LIB_IMPORTS            = [fullPath('./node_modules/@popperjs/core/dist/umd/popper.js')]
const JS_ASSET_IMPORTS          = [
                                    DIR_CIVICTHEME ? `${DIR_CIVICTHEME}/assets/js/**/*.js` : false,
                                    `${DIR_ASSETS_IN}/js/**/*.js`,
                                  ].filter(Boolean)
const JS_LINT_EXCLUSION_HEADER  = '// phpcs:ignoreFile'

const CONSTANTS_FILE_OUT        = `${DIR_OUT}/constants.json`
const CONSTANTS_SCSS_IMPORTER   = fullPath(`./.storybook/importer.scss_variables.js`)
const CONSTANTS_BACKGROUND_UTIL = `${COMPONENT_DIR}/00-base/background/background.utils.js`
const CONSTANTS_ICON_UTIL       = `${COMPONENT_DIR}/00-base/icon/icon.utils.js`
const CONSTANTS_LOGO_UTIL       = `${COMPONENT_DIR}/02-molecules/logo/logo.utils.js`

const STYLE_SDC_COMMON_INCLUDES      = [VAR_CT_ASSETS_DIRECTORY, `@import '00-base/variables';`]
const STYLE_SDC_MIXIN_IMPORTS        = `00-base/mixins/**/*.scss`
const STYLE_SDC_BASE_IMPORTS         = `00-base/**/!(*.stories|variables|_variables.*).scss`
const STYLE_SDC_BASE_FILE_OUT        = `${DIR_OUT}/${STYLE_NAME}.base.css`;
const JS_SDC_BASE_FILE_OUT           = `${DIR_OUT}/${SCRIPT_NAME}.drupal.base.js`
const JS_SDC_STORYBOOK_BASE_FILE_OUT = `${DIR_OUT}/${SCRIPT_NAME}.base.js`
const JS_SDC_BASE_IMPORTS            = `${COMPONENT_DIR}/00-base/**/!(*.stories|*.test|*.data|*.stories.data|*.utils).js`
const SDC_HEADER                     = '/**\n * This file was automatically generated. Please run `npm run dist` to update.\n */\n\n';
const SDC_STYLE_FILES_IN             = `!(00-base)/**/*.scss`
const SDC_COMPONENT_DIR              = config.base ? DIR_COMPONENTS_IN : DIR_COMPONENTS_IN
const SDC_COMPLETE_COMPONENT_DIR     = config.base ? DIR_COMPONENTS_IN : DIR_COMPONENTS_OUT

if (config.build) {
  build()
}

if (config.watch) {
  watch()
}

if (config.cli) {
  cli()
}

if (config.lintex) {
  lintExclusions()
}

// ----------------------------------------------------------------------------- BUILD STEPS

function buildOutDirectory() {
  if (!fs.existsSync(DIR_OUT)) {
    fs.mkdirSync(DIR_OUT)
  }
}

function buildCombineDirectories() {
  if (config.combine && !config.base) {
    runCommand(`rsync -a --delete ${DIR_UIKIT_COMPONENTS_IN}/ ${DIR_UIKIT_COPY_OUT}/`)
    runCommand(`rsync -a --delete ${DIR_UIKIT_COPY_OUT}/ ${DIR_COMPONENTS_OUT}/`)
    runCommand(`rsync -a ${DIR_COMPONENTS_IN}/ ${DIR_COMPONENTS_OUT}/`)
    successReporter(`Saved: Combined folders ${time()}`)
  }
}

function buildStyles() {
  if (config.styles) {
    const stylecss = [
      VAR_CT_ASSETS_DIRECTORY,
      loadStyleFile(STYLE_FILE_IN, COMPONENT_DIR),
      config.styles_variables ? getImportsFromGlob(STYLE_VARIABLE_FILE_IN, COMPONENT_DIR) : '',
      getImportsFromGlob(STYLE_THEME_FILE_IN, PATH),
      config.base ? [
        config.styles_admin ? getImportsFromGlob(STYLE_ADMIN_FILE_IN, PATH) : '',
        config.styles_layout ? loadStyleFile(STYLE_LAYOUT_FILE_IN, PATH) : '',
      ].join('\n') : '',
    ].join('\n')

    const compiled = sass.compileString(stylecss, { loadPaths: [COMPONENT_DIR, PATH] })
    const compiledImportAtTop = sortCssLines(compiled.css)
    fs.writeFileSync(STYLE_FILE_OUT, compiledImportAtTop, 'utf-8')
    successReporter(`Saved: Component styles ${time()}`)
  }
}

function buildStylesStorybook() {
  if (config.styles && config.styles_storybook) {
    // Replace the asset path.
    let file = fs.readFileSync(STYLE_FILE_OUT, 'utf-8')
    file = file.replaceAll(DIR_CT_ASSETS, DIR_SB_ASSETS)
    fs.writeFileSync(STYLE_STORYBOOK_FILE_OUT, file, 'utf-8')
    successReporter(`Saved: Component styles (storybook) ${time()}`)
  }
}

function buildStylesEditor() {
  if (config.styles_editor) {
    const editorcss = [
      VAR_CT_ASSETS_DIRECTORY,
      loadStyleFile(STYLE_EDITOR_FILE_IN, PATH),
    ].join('\n')

    const compiled = sass.compileString(editorcss, { loadPaths: [PATH] })
    fs.writeFileSync(STYLE_EDITOR_FILE_OUT, compiled.css, 'utf-8')
    successReporter(`Saved: Editor styles ${time()}`)
  }
}

function buildStylesAdmin() {
  if (config.styles_admin) {
    const compiled = sass.compile(STYLE_ADMIN_FILE_IN, { loadPaths: [PATH] })
    fs.writeFileSync(STYLE_ADMIN_FILE_OUT, compiled.css, 'utf-8')
    successReporter(`Saved: Admin styles ${time()}`)
  }
}

function buildStylesLayout() {
  if (config.styles_layout) {
    const layoutcss = [
      VAR_CT_ASSETS_DIRECTORY,
      loadStyleFile(STYLE_LAYOUT_FILE_IN, PATH),
    ].join('\n')

    const compiled = sass.compileString(layoutcss, { loadPaths: [PATH] })
    fs.writeFileSync(STYLE_LAYOUT_FILE_OUT, compiled.css, 'utf-8')
    successReporter(`Saved: Layout styles ${time()}`)
  }
}

function buildStylesVariables() {
  if (config.styles_variables) {
    const compiled = sass.compile(STYLE_VARIABLE_FILE_IN, { loadPaths: [COMPONENT_DIR] })
    fs.writeFileSync(STYLE_VARIABLE_FILE_OUT, compiled.css, 'utf-8')
    successReporter(`Saved: Variable styles ${time()}`)
  }
}

function buildStylesStories() {
  if (config.styles_stories) {
    const storybookcss = [
      VAR_SB_ASSETS_DIRECTORY,
      loadStyleFile(STYLE_STORIES_FILE_IN, COMPONENT_DIR),
    ].join('\n')

    const compiled = sass.compileString(storybookcss, { loadPaths: [COMPONENT_DIR, PATH] })
    fs.writeFileSync(STYLE_STORIES_FILE_OUT, compiled.css, 'utf-8')
    successReporter(`Saved: Stories styles ${time()}`)
  }
}

function buildStylesTheme() {
  if (config.styles_theme) {
    const themecss = [
      VAR_CT_ASSETS_DIRECTORY,
      getImportsFromGlob(STYLE_THEME_FILE_IN, PATH),
    ].join('\n')

    const compiled = sass.compileString(themecss, { loadPaths: [COMPONENT_DIR, PATH] })
    fs.writeFileSync(STYLE_THEME_FILE_OUT, sortCssLines(compiled.css), 'utf-8')
    successReporter(`Saved: Component styles (theme) ${time()}`)
  }
}

function buildStylesSdcBase() {
  if (config.sdc_base) {
    const baseCss = [
      ...STYLE_SDC_COMMON_INCLUDES,
      getImportsFromGlob(STYLE_SDC_BASE_IMPORTS, COMPONENT_DIR),
    ].join('\n')
    const compiled = sass.compileString(baseCss, { loadPaths: [COMPONENT_DIR] })
    fs.writeFileSync(STYLE_SDC_BASE_FILE_OUT, SDC_HEADER + sortCssLines(compiled.css))
    successReporter(`Saved: SDC styles (base) ${time()}`)
  }
}

async function buildStylesSdcComponents() {
  if (config.sdc_components) {
    const componentFiles = globSync(SDC_STYLE_FILES_IN, { cwd: SDC_COMPONENT_DIR })

    const componentDependencies = [
      ...STYLE_SDC_COMMON_INCLUDES,
      getImportsFromGlob(STYLE_SDC_MIXIN_IMPORTS, SDC_COMPLETE_COMPONENT_DIR)
    ].join('\n')

    await Promise.all(componentFiles.map(async (filePath) => {
      const separator = filePath.lastIndexOf('/') + 1
      const styleDir = filePath.substring(0, separator)
      const styleName = filePath.substring(separator, filePath.lastIndexOf('.'))
      const styleData = `${componentDependencies}\n@import '${filePath}';`
      const result = await sass.compileStringAsync(styleData, { loadPaths: [SDC_COMPLETE_COMPONENT_DIR] })
      fs.writeFileSync(`${SDC_COMPONENT_DIR}/${styleDir}/${styleName}.css`, SDC_HEADER + result.css)
    }))
    successReporter(`Saved: SDC styles (components) ${time()}`)
  }
}

function buildStylesSdcCopyBack() {
  if (config.sdc_components && !config.base) {
    // Copy generated css files into the complete components directory.
    globSync(SDC_STYLE_FILES_IN, { cwd: SDC_COMPONENT_DIR }).map(path => {
      const filename = `${path.substring(0, path.lastIndexOf('.'))}.css`
      return {
        from: `${SDC_COMPONENT_DIR}/${filename}`,
        to: `${SDC_COMPLETE_COMPONENT_DIR}/${filename}`
      }
    }).forEach(file => fs.copyFileSync(file.from, file.to))
    successReporter(`Saved: SDC styles copied to combined components ${time()}`)
  }
}

function buildJavascriptSdcBase() {
  if (config.sdc_base) {
    const libJs = [
      ...JS_LIB_IMPORTS,
      ...globSync(JS_ASSET_IMPORTS)
    ].map(loadJSFile).join('\n')

    // Load base components after DOM is ready
    const baseComponentJs = globSync(JS_SDC_BASE_IMPORTS).map(loadJSFile).join('\n')

    // Write JS file with drupal behaviour wrapper.
    const newDrupalBaseJs = [
      JS_LINT_EXCLUSION_HEADER,
      libJs,
      `Drupal.behaviors.${THEME_NAME} = {attach: function (context, settings) {\n${baseComponentJs}\n}};`
    ].join('\n')
    fs.writeFileSync(JS_SDC_BASE_FILE_OUT, newDrupalBaseJs, 'utf-8')
    successReporter(`Saved: SDC javascript base (drupal) ${time()}`)

    // Write JS file with dom content loaded wrapper.
    const newBaseJs = [
      JS_LINT_EXCLUSION_HEADER,
      libJs,
      `document.addEventListener('DOMContentLoaded', () => {\n${baseComponentJs}\n});`
    ].join('\n')
    fs.writeFileSync(JS_SDC_STORYBOOK_BASE_FILE_OUT, newBaseJs, 'utf-8')
    successReporter(`Saved: SDC javascript base (storybook) ${time()}`)
  }
}

function buildJavascript() {
  if (config.js_drupal || config.js_storybook) {
    const libJs = [
      ...JS_LIB_IMPORTS,
      ...globSync(JS_ASSET_IMPORTS)
    ].map(loadJSFile).join('\n')

    const components = globSync(JS_CIVIC_IMPORTS).map(filename => ({
      name: `${THEME_NAME}_${filename.split('/').reverse()[0].replace('.js', '').replace(/-/g, '_')}`,
      body: fs.readFileSync(filename, 'utf-8')
    }))

    // Write JS file with drupal behaviour wrapper.
    if (config.js_drupal) {
      fs.writeFileSync(JS_FILE_OUT, [
        JS_LINT_EXCLUSION_HEADER,
        libJs,
        ...components.map(i => {
          return `Drupal.behaviors.${i.name} = {attach: function (context, settings) {\n${i.body}\n}};`
        })
      ].join('\n'), 'utf-8')
      successReporter(`Saved: Compiled javascript (drupal) ${time()}`)
    }

    // Write JS file with dom content loaded wrapper.
    if (config.js_storybook) {
      fs.writeFileSync(JS_STORYBOOK_FILE_OUT, [
        JS_LINT_EXCLUSION_HEADER,
        libJs,
        ...components.map(i => {
          return `document.addEventListener('DOMContentLoaded', () => {\n${i.body}\n});`
        })
      ].join('\n'), 'utf-8')
      successReporter(`Saved: Compiled javascript (storybook) ${time()}`)
    }
  }
}

function buildAssetsDirectory() {
  if (config.assets) {
    runCommand(`rsync -a --delete --prune-empty-dirs --exclude .gitkeep --exclude js --exclude sass ${DIR_ASSETS_IN}/ ${DIR_ASSETS_OUT}/`)
    successReporter(`Saved: Assets ${time()}`)
  }
}

async function buildConstants() {
  if (config.constants) {
    const { default: scssVariableImporter } = await import(CONSTANTS_SCSS_IMPORTER);
    const { default: backgroundUtils } = await import(CONSTANTS_BACKGROUND_UTIL);
    const { default: iconUtils } = await import(CONSTANTS_ICON_UTIL);
    const { default: logoUtils } = await import(CONSTANTS_LOGO_UTIL);

    const constants = {
      BACKGROUNDS: backgroundUtils.getBackgrounds(),
      ICONS: iconUtils.getIcons(),
      LOGOS: logoUtils.getLogos(),
      SCSS_VARIABLES: scssVariableImporter.getVariables(),
    }
    fs.writeFileSync(CONSTANTS_FILE_OUT, JSON.stringify(constants, null, 2), 'utf-8')
    successReporter(`Saved: Compiled constants ${time()}`)
  }
}

// ----------------------------------------------------------------------------- CORE FEATURES

async function build() {
  startTime = new Date().getTime()
  lastTime = startTime
  try {
    buildOutDirectory()
    buildCombineDirectories()
    buildStyles()
    buildStylesStorybook()
    buildStylesEditor()
    buildStylesAdmin()
    buildStylesLayout()
    buildStylesVariables()
    buildStylesStories()
    buildStylesTheme()
    buildStylesSdcBase()
    await buildStylesSdcComponents()
    buildStylesSdcCopyBack()
    buildJavascript()
    buildJavascriptSdcBase()
    buildAssetsDirectory()
    await buildConstants()
  } catch (error) {
    errorReporter(error);
  }

  console.log(`Time taken: ${time(true)}`)
}

function watch() {
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

function cli() {
  const scripts = flags.slice(1)
  console.log(`Running npm commands: ${scripts.join(' && ')}`)
  scripts.forEach((script, idx) => {
    const child = spawn('npm', ['run', script])
    child.stdout.on('data', (data) => {
      const color = `\x1b[3${Math.min(idx, 3) + 3}m`
      const sData = data.toString().replaceAll(/[\u001b\u009b][[()#;?]*(?:[0-9]{1,4}(?:;[0-9]{0,4})*)?[0-9A-ORZcf-nqry=><]/g, '') // strip ANSI colours
      process.stdout.write(`${color}${sData}`)
    })
  })
}

function lintExclusions() {
  const lintExclusionPaths = [
    fullPath('./storybook-static/**/*.js'),
    fullPath('./dist/**/*.js'),
    fullPath('./components/**/*.js'),
    fullPath('./.storybook/*.js'),
  ]
  console.log(`Applying lint exclusions: ${lintExclusionPaths.join(', ')}`)
  const header = `${JS_LINT_EXCLUSION_HEADER}\n`
  lintExclusionPaths.forEach((lintExclusionPath) => {
    globSync(lintExclusionPath).forEach(filename => {
      const data = fs.readFileSync(filename, 'utf-8')
      if (data.substr(0, header.length) !== header) {
        fs.writeFileSync(filename, `${header}${data}`, 'utf-8')
      }
    })
  });
}

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

function getImportsFromGlob(path, cwd) {
  return globSync(path, { cwd }).sort((a, b) => a.localeCompare(b)).map(i => `@import "${i}";`).join('\n')
}

function loadStyleFile(path, cwd) {
  const result = []
  let data = fs.readFileSync(path, 'utf-8')

  data.split('\n').forEach(line => {
    // Only glob imports with *!()| characters.
    const match = /@import '(.*[\*!()|].*)'/.exec(line)
    result.push(match ? getImportsFromGlob(match[1], cwd) : line)
  })

  return result.join('\n')
}

function loadJSFile(filename) {
  return stripJS(fs.readFileSync(filename, 'utf-8'))
}

function stripJS(js) {
  return js.replace(/\/\/# sourceMappingURL=.*\.(map|json)/gi, '')
}

function fullPath(filepath) {
  return path.resolve(PATH, filepath)
}

function getCivicthemeDir(subthemeDir, parent, civicthemeGlob) {
  if (fs.existsSync(BUILD_CONFIG_DIR)) {
    const config = JSON.parse(fs.readFileSync(BUILD_CONFIG_DIR, 'utf-8'))
    if (config.civicthemeGlob === civicthemeGlob && fs.existsSync(config.civicthemePath)) {
      return config.civicthemePath
    }
  }
  let civicthemePath = getDirInParent(subthemeDir, parent, civicthemeGlob)
  if (civicthemePath) {
    civicthemePath = path.relative(subthemeDir, civicthemePath)
    fs.writeFileSync(BUILD_CONFIG_DIR, JSON.stringify({ civicthemeGlob, civicthemePath }, null, 2), 'utf-8')
    return civicthemePath
  }
  errorReporter({
    message: 'Could not find civictheme directory.',
    formatted: `Unable to find '${civicthemeGlob}' from '${parent}' directory.`
  }, true)
}

function getDirInParent(currentDir, parent, destinationGlob) {
  const pathParts = currentDir.split('/')
  const parentIndex = pathParts.indexOf(parent)
  const basePath = parentIndex >= 0 ? pathParts.slice(0, parentIndex + 1).join('/') : null
  return basePath ? globSync(`${basePath}${destinationGlob}`, { ignore: 'node_modules/**' }).pop() : null
}

function time(full) {
  const now = new Date().getTime()
  const rtn = now - (full ? startTime : lastTime)
  lastTime = now
  return `[ ${rtn} ms ]`
}

function errorReporter(error, fatal = false) {
  console.error('❌   Error during build:', error.message);
  console.error('Details:', error.formatted || error);
  if (fatal) {
    process.exit(1)
  }
}

function successReporter(message) {
  console.log(`✅   ${message}`)
}

function sortCssLines(css) {
  return css.split('\n')
    .sort(a => a.indexOf('@import') === 0 ? -1 : 0)
    .sort(a => a.indexOf('@charset') === 0 ? -1 : 0)
    .join('\n')
}
