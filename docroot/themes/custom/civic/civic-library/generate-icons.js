const fs = require('fs')

const paths = fs.readdirSync('./assets/icons')
const basePath = './assets/icons'

function getMatches (reg, text, matchIndex) {
  const returnVars = []
  let match = reg.exec(text)
  while (match !== null) {
    returnVars.push(match[matchIndex])
    match = reg.exec(text)
  }
  return returnVars
}

// Get icons.
const iconPaths = []
paths.forEach(path => {
  const currentPath = `${basePath}/${path}`
  const isDir = fs.lstatSync(currentPath).isDirectory()
  if (isDir) {
    const iconFiles = fs.readdirSync(currentPath)
    iconFiles.forEach(file => {
      iconPaths.push(`${currentPath}/${file}`)
    })
  }
})

// Extract the name, width and path out of them.
const twigVariables = []
iconPaths.forEach(path => {
  const iconSvg = fs.readFileSync(path, 'utf-8')

  const rPaths = new RegExp(' d="([A-Z0-9\\.\\s]+)"', 'gi')
  const rWidth = new RegExp('width="([0-9]+)"', 'gi')
  const rHeight = new RegExp('height="([0-9]+)"', 'gi')

  const paths = getMatches(rPaths, iconSvg, 1)
  const width = getMatches(rWidth, iconSvg, 1)
  const height = getMatches(rHeight, iconSvg, 1)
  const name = path.replace(basePath, '').substring(1).replace('.svg', '').replace(/[\/\-]/g, '_').toLowerCase().replace(/[^a-z0-9\-_]+/g, '')

  const renderedPath = `[${paths.map(item => `"${item}"`).join(',')}]`
  const twigVar = `"${name}": { "width": ${width[0]}, "height": ${height[0]}, "paths": ${renderedPath} }`
  twigVariables.push(twigVar)
})

// Save to an icon file.
fs.writeFileSync('./components/01-atoms/icon/icon_library.twig', `{% set icons = {\n  ${twigVariables.join(',\n  ')}\n} %}\n{% block content %}{% endblock %}`);
