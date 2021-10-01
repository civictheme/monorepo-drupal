const iconUtils = require('../scripts/icons.js')

module.exports = {
  getIcons () {
    return {
      icons: iconUtils.getIcons(),
      packs: iconUtils.getIconPacks()
    }
  }
}
