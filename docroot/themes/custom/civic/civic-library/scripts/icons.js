const fs = require('fs')
const path = require('path')

const basePath = path.resolve(__dirname, '../assets/icons')
const paths = fs.readdirSync(basePath)

function getIcons() {
  return getIconPaths().map(path => getNameFromPath(path))
}

function getIconPacks() {
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

function getIconPaths() {
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
  return iconPaths
}

function getNameFromPath(path) {
  return path.replace(basePath, '').substring(1).replace('.svg', '').replace(/[\/\-]/g, '_').toLowerCase().replace(/[^a-z0-9\-_]+/g, '')
}

module.exports = {
  getIcons,
  getIconPacks
}
