// phpcs:ignoreFile
//
// Extract CSS variables as object from SCSS file.
//
const fs = require('fs');
const extractor = require('@alexskrypnyk/scss-variables-extractor');

const files = [
  './components_combined/00-base/_variables.base.scss',
  './components_combined/variables.base.scss',
  './components_combined/00-base/_variables.components.scss',
  './components_combined/variables.components.scss',
];

function getVariables() {
  let variables = {};

  for (const i in files) {
    const content = fs.readFileSync(files[i], { encoding: 'utf8' });
    variables = { ...variables, ...extractor.extract(content) };
  }

  return variables;
}

module.exports = {
  getVariables,
};
