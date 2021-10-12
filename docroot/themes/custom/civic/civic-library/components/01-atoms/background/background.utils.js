const fs = require('fs');
const pathUtil = require('path');

const dir = '../../../assets/backgrounds';
const basePath = pathUtil.resolve(__dirname, dir);
const paths = fs.readdirSync(basePath);

function getBackgrounds() {
  const urls = {};
  paths.forEach((path) => {
    urls[path] = `${dir.replace('../../../assets/', '')}/${path}`;
  });
  return urls;
}

module.exports = {
  getBackgrounds,
};
