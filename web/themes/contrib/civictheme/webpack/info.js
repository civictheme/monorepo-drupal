// phpcs:ignoreFile
const fs = require('fs');
const path = require('path');

const options = {
  paths: [
    'node_modules',
  ],
};
/* eslint-disable */
const boxen = require(require.resolve('boxen', options));
const chalk = require(require.resolve('chalk', options));
const dedent = require(require.resolve('dedent', options));
const glob = require(require.resolve('glob-gitignore', options));
const semver = require(require.resolve('semver', options));
/* eslint-enable */

const defaultInfo = {
  version: '1.99.0',
  homepage: 'https://github.com/salsadigitalauorg/civictheme',
  bugs: 'https://github.com/salsadigitalauorg/civictheme_source/issues',
  repository: 'https://github.com/salsadigitalauorg/civictheme_source.git',
};

function cleanVersionString(version) {
  return version ? semver.coerce(version.toString()).toString() : version;
}

function loadPackageJson(filename) {
  const filepath = filename || process.env.npm_package_json || 'package.json';
  if (!fs.existsSync(filepath)) {
    throw new Error('Unable to find package.json');
  }
  return JSON.parse(fs.readFileSync(filepath, 'utf8'));
}

function info(packagejson) {
  const json = loadPackageJson(packagejson);
  const section = Object.prototype.hasOwnProperty.call(json, 'civictheme') ? json.civictheme : json;
  return Object.assign(defaultInfo, section);
}

function findProjectRoot(dir) {
  const search = '.git';

  let found = dir;
  const parts = dir.split(path.sep);
  while (parts.length > 0) {
    if (fs.existsSync(parts.join(path.sep) + path.sep + search)) {
      found = parts.join(path.sep);
      break;
    }
    parts.pop();
  }

  return found;
}

function findBasePackageJson(dir) {
  const repoRoot = findProjectRoot(dir);

  let found = null;
  const files = glob.sync('**/package.json', {
    cwd: path.resolve(repoRoot),
    ignore: [
      'node_modules',
      'vendor',
      'core',
    ],
  });

  for (const i in files) {
    const file = repoRoot + path.sep + files[i];
    const json = loadPackageJson(file);
    if (Object.prototype.hasOwnProperty.call(json, 'name') && json.name === 'civictheme-theme') {
      found = file;
      break;
    }
  }

  return found;
}

function isSubtheme(packagejson) {
  return Object.prototype.hasOwnProperty.call(loadPackageJson(packagejson), 'civictheme');
}

const currentVersion = function (packagejson) {
  return cleanVersionString(loadPackageJson(packagejson).version);
};

const baseVersion = function (packagejson) {
  return cleanVersionString(info(packagejson).version);
};

const isOutdated = function () {
  return semver.lt(semver.coerce(baseVersion()), semver.coerce(baseVersion(findBasePackageJson(__dirname))));
};

const printHeader = function (packagejson) {
  const json = loadPackageJson(packagejson);
  const { name } = json;
  const description = Object.prototype.hasOwnProperty.call(json, 'description') ? json.description : 'CivicTheme is an open source, inclusive and component-based design system.';

  let baseThemeMessage = '';
  if (isSubtheme(packagejson)) {
    baseThemeMessage = `

    This is a Drupal sub-theme of the CivicTheme Drupal theme.
    It was created from CivicTheme starter kit version ${chalk.bold(baseVersion() || 'not specified')}.

    ${chalk.bold(`Currently installed CivicTheme version is ${baseVersion(findBasePackageJson(__dirname))}`)}.${isOutdated() ? `

    ${chalk.bold(`UPDATE REQUIRED`)}
    Please update your theme to the latest files from the starter kit.
    Once complete, please update a "civictheme.version" in your theme
    package.json to the currently installed CivicTheme version.

    See more: https://docs.civictheme.io/upgrade` : ''}`;
  }

  // eslint-disable-next-line no-console
  console.log(boxen(dedent`
    Name:        ${name}
    Description: ${description}
    Version:     ${currentVersion() || 'Not specified in package.json'}${baseThemeMessage}

    ${chalk.gray('Homepage: ')}   ${info().homepage}
    ${chalk.gray('Issues: ')}     ${info().bugs}
    ${chalk.gray('Repository: ')} ${info().repository}

    `, {
    title: 'CivicTheme',
    titleAlignment: 'center',
    borderStyle: 'round',
    padding: 1,
    color: '#00698f',
    borderColor: '#00698f',
  }));
};

module.exports = {
  currentVersion,
  baseVersion,
  printHeader,
  isOutdated,
};
