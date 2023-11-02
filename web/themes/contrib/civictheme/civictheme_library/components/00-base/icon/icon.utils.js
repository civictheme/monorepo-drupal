// phpcs:ignoreFile
/**
 * @file
 * Icon component utilities.
 */

const fs = require('fs');
const pathUtil = require('path');

const basePath = pathUtil.resolve(__dirname, '../../../assets/icons');

function getIconPaths() {
  const iconPaths = [];
  const iconFiles = fs.readdirSync(basePath);
  const EXTENSION = '.svg';

  iconFiles.forEach((file) => {
    if (pathUtil.extname(file).toLowerCase() === EXTENSION) {
      iconPaths.push(`${basePath}/${file}`);
    }
  });

  return iconPaths;
}

function getIconNameFromPath(path) {
  return path.replace(basePath, '').substring(1).replace('.svg', '');
}

function getIcons() {
  return getIconPaths().map((path) => getIconNameFromPath(path));
}

module.exports = {
  getIcons,
};
