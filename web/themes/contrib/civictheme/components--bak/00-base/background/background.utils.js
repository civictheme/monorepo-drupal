// phpcs:ignoreFile
/**
 * @file
 * Background component utilities.
 */

import fs from 'fs';
import pathUtil from 'path';

const dir = '../../../assets/backgrounds';
const basePath = pathUtil.resolve(import.meta.dirname, dir);
const paths = fs.readdirSync(basePath);

function getBackgrounds() {
  const urls = {};
  paths.forEach((path) => {
    urls[path] = `${dir.replace('../../../', '')}/${path}`;
  });
  return urls;
}

export default {
  getBackgrounds,
};
