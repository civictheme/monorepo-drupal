/**
 * @file
 * Colors component utilities.
 */

const fs = require('fs');
const pathUtil = require('path');

const dir = '../../../../dist';
const basePath = pathUtil.resolve(__dirname, dir);
const paths = fs.readdirSync(basePath);

function getVariablesCsvPath() {
  const url = {};
  paths.forEach((path) => {
    const csvPath = path.match(/.*\.variables.css/);
    if (csvPath) {
      url['path'] = `${dir.replace('../../../', '')}/${path.replace('.css', '.csv')}`;
    }
  });
  return url;
}

module.exports = {
  getVariablesCsvPath,
};
