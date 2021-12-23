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
        const type = match[1] === 'mobile' ? 'mobile' : 'desktop';
        const theme = match[2] === 'dark' ? 'dark' : 'light';
        urls[type] = urls[type] || {};
        urls[type][theme] = urls[type][theme] || {};
        urls[type][theme] = `${dir.replace('../../../', '')}/${path}`;
      }
    }
  });

  return urls;
}

module.exports = {
  getLogos,
};
