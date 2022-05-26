/**
 * @file
 * Icon component utilities.
 */

const fs = require('fs');
const pathUtil = require('path');

const basePath = pathUtil.resolve(__dirname, '../../../assets/icons');
const paths = fs.readdirSync(basePath);

function getIconPaths() {
  const iconPaths = [];
  paths.forEach((path) => {
    const currentPath = `${basePath}/${path}`;
    const isDir = fs.lstatSync(currentPath).isDirectory();
    if (isDir) {
      const iconFiles = fs.readdirSync(currentPath);
      iconFiles.forEach((file) => {
        iconPaths.push(`${currentPath}/${file}`);
      });
    }
  });
  return iconPaths;
}

function getIconNameFromPath(path) {
  return path.replace(basePath, '').substring(1).replace('.svg', '').replace(/[/-]/g, '_')
    .toLowerCase()
    .replace(/[^a-z0-9\-_]+/g, '');
}

function getIcons() {
  return getIconPaths().map((path) => getIconNameFromPath(path));
}

function getIconPacks() {
  const icons = getIcons();
  const packs = {};
  icons.forEach((icon) => {
    const packName = icon.substring(0, icon.indexOf('_'));
    if (!packs[packName]) {
      packs[packName] = [];
    }
    packs[packName].push(icon);
  });
  return packs;
}

module.exports = {
  getIcons,
  getIconPacks,
  getIconPaths,
  getIconNameFromPath,
};
