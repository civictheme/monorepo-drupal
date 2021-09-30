const iconUtils = require('../scripts/icons.js')

function getIcons () {
  const basePath = iconUtils.getBasePath()
  const icons = iconUtils.getIconPaths().map(path => iconUtils.getNameFromPath(path))
  return icons
}

// Use the icons available in the assets directory to compile a list of spritesheets and icon IDs.
function getIconPacks () {
  const icons = getIcons()
  const packs = {}
  icons.forEach(icon => {
    const packName = icon.substring(0, icon.indexOf('_'))
    if (!packs[packName]) {
      packs[packName] = []
    }
    packs[packName].push(icon)
  })
  return packs
}

module.exports = {
  getIcons () {
    return {
      icons: getIcons(),
      packs: getIconPacks()
    }
  }
}
