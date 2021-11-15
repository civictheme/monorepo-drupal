//
// Extract CSS variables as object from SCSS file.
//
const fs = require('fs');

const importedFiles = [
  './components/00-base/_variables.scss',
  './components/variables.scss',
];

function removeComments(string) {
  let lines = string.split(/\r?\n/);
  lines = lines.filter((line) => !/\s*\/\/[^\n]*/.test(line));
  return lines.join('\n');
}

function getSassVariablesFromValue(value) {
  const vars = [];

  const re = new RegExp('\'[a-z-_0-9]+\'', 'gi');

  let match = re.exec(value);
  while (match !== null) {
    vars.push(match[0].replace(/[':]/gi, ''));
    match = re.exec(value);
  }

  return vars;
}

function getVariablesFromFile(file) {
  const allValues = {};

  let importedFile = fs.readFileSync(file, {encoding: 'utf8'});

  importedFile = removeComments(importedFile);

  const values = importedFile.split(/;(?=(?:[^"']*['"][^"']*['"])*[^"']*$)/gi);

  // Extract variables from every value expression.
  values.forEach((value) => {
    const re = new RegExp('(\\$[a-z-_][a-z-_0-9]+:)', 'gim');
    const match = re.exec(value);
    if (match) {
      const name = match[0].replace(/[$:]/gi, '');
      let valueStripped = value.replace(/\$[a-z-]+:/gi, '');
      valueStripped = valueStripped.replace(/\(.+?\)/gi, '');
      valueStripped = valueStripped.replace(/:\s\([\s\S\n\r]+?\)/gi, '');
      allValues[name] = getSassVariablesFromValue(valueStripped);
    }
  });

  return allValues;
}

function getVariables() {
  let variables = {};
  for (const i in importedFiles) {
    variables = { ...variables, ...getVariablesFromFile(importedFiles[i]) };
  }

  return variables;
}

module.exports = {
  getVariables,
};
