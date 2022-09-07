// phpcs:ignoreFile
/**
 * @file
 * Logo component utilities.
 */

const fs = require('fs');
const pathUtil = require('path');

const dir = '../../../assets/logos';
const basePath = pathUtil.resolve(__dirname, dir);
const paths = fs.readdirSync(basePath);

function getLogos() {
  const urls = {};
  paths.forEach((path) => {
    const matches = path.matchAll(/[^_]+_logo_([^_]+)+_([^.]+)/g);
    for (const match of matches) {
      if (match.length >= 3) {
        const theme = match[1] === 'dark' ? 'dark' : 'light';
        const type = match[2] === 'mobile' ? 'mobile' : 'desktop';
        urls[theme] = urls[theme] || {};
        urls[theme][type] = urls[theme][type] || {};
        urls[theme][type] = `${dir.replace('../../../', '')}/${path}`;
      }
    }
  });

  return urls;
}

function getSecondaryLogos() {
  const urls = {};
  paths.forEach((path) => {
    const matches = path.matchAll(/[^_]+_logo_secondary_([^_]+)+_([^.]+)/g);
    for (const match of matches) {
      if (match.length >= 3) {
        const theme = match[1] === 'dark' ? 'dark' : 'light';
        const type = match[2] === 'mobile' ? 'mobile' : 'desktop';
        urls[theme] = urls[theme] || {};
        urls[theme][type] = urls[theme][type] || {};
        urls[theme][type] = `${dir.replace('../../../', '')}/${path}`;
      }
    }
  });

  return urls;
}

module.exports = {
  getLogos,
  getSecondaryLogos,
};
