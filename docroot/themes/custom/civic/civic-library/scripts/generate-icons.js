const fs = require('fs')
const iconUtils = require('./icons.js')

const basePath = iconUtils.getBasePath()

function getMatches (reg, text, matchIndex) {
  const returnVars = []
  let match = reg.exec(text)
  while (match !== null) {
    returnVars.push(matchIndex ? match[matchIndex] : match)
    match = reg.exec(text)
  }
  return returnVars
}

function renderPath (path) {
  return Object.keys(path).map(key => `"${key}": "${path[key]}"`).join(', ')
}

// Extract the name, width and path out of them.
const twigVariables = []
iconUtils.getIconPaths().forEach(path => {
  const iconSvg = fs.readFileSync(path, 'utf-8')

  const rWidth = new RegExp('width="([0-9]+)"', 'gi')
  const rHeight = new RegExp('height="([0-9]+)"', 'gi')
  const rPath = new RegExp('<path ([^\\/]+?)\\/>', 'gi')
  const rAttr = new RegExp('([a-zA-Z\\-]+)="(.+?)"', 'gi')

  const paths = getMatches(rPath, iconSvg, 1)
  let pathArr = []
  paths.forEach(pathHTML => {
    const pathProps = {}
    getMatches(rAttr, pathHTML, null).forEach(match => {
      pathProps[match[1]] = match[2]
    })
    pathArr.push(pathProps)
  })

  const renderedPath = `[ ${pathArr.map(path => `{${renderPath(path)}}`)} ]`
  const width = getMatches(rWidth, iconSvg, 1)
  const height = getMatches(rHeight, iconSvg, 1)
  const name = iconUtils.getNameFromPath(path)

  const twigVar = `"${name}": { "width": ${width[0]}, "height": ${height[0]}, "paths": ${renderedPath} }`
  twigVariables.push(twigVar)
})

// Save to an icon file.
fs.writeFileSync('./components/01-atoms/icon/icon_library.twig', `{% set icons = {\n  ${twigVariables.join(',\n  ')}\n} %}\n{% block content %}{% endblock %}`);
