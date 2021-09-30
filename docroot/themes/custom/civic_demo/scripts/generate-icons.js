const fs = require('fs')
const iconUtils = require('./icons.js')

const basePath = iconUtils.getBasePath()

function getMatches (reg, text, matchIndex) {
  const returnVars = []
  let match = reg.exec(text)
  while (match !== null) {
    returnVars.push(match[matchIndex])
    match = reg.exec(text)
  }
  return returnVars
}

// Extract the name, width and path out of them.
const twigVariables = []
iconUtils.getIconPaths().forEach(path => {
  const iconSvg = fs.readFileSync(path, 'utf-8')

  const rPaths = new RegExp(' d="([A-Z0-9\\.\\s]+)"', 'gi')
  const rWidth = new RegExp('width="([0-9]+)"', 'gi')
  const rHeight = new RegExp('height="([0-9]+)"', 'gi')

  const paths = getMatches(rPaths, iconSvg, 1)
  const width = getMatches(rWidth, iconSvg, 1)
  const height = getMatches(rHeight, iconSvg, 1)
  const name = iconUtils.getNameFromPath(path)

  const renderedPath = `[${paths.map(item => `"${item}"`).join(',')}]`
  const twigVar = `"${name}": { "width": ${width[0]}, "height": ${height[0]}, "paths": ${renderedPath} }`
  twigVariables.push(twigVar)
})

// Save to an icon file.
fs.writeFileSync('./components/01-atoms/icon/icon_library.twig', `{% set icons = {\n  ${twigVariables.join(',\n  ')}\n} %}\n{% block content %}{% endblock %}`);
