const fs = require('fs')

const basePath = './assets/icons'
const paths = fs.readdirSync(basePath)

module.exports = {
  getBasePath () {
    return basePath
  },
  // Get icons paths
  getIconPaths () {
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
  },
  getNameFromPath (path) {
    return path.replace(basePath, '').substring(1).replace('.svg', '').replace(/[\/\-]/g, '_').toLowerCase().replace(/[^a-z0-9\-_]+/g, '')
  }
}
