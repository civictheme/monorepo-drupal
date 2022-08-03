// phpcs:ignoreFile
//
// Extract CSS variables as object from SCSS file.
//
const fs = require('fs');

const importedFiles = [
  './components/00-base/_variables.base.scss',
  './components/variables.base.scss',
  './components/00-base/_variables.components.scss',
  './components/variables.components.scss',
];

function removeComments(string) {
  let lines = string.split(/\r?\n/);
  lines = lines.filter((line) => !/\s*\/\/[^\n]*/.test(line));
  return lines.join('\n');
}

function getSassVariablesFromValueMap(string, maxLevel) {
  maxLevel = maxLevel || 1;

  const tokens = [];
  let braces = 0;
  let quotes = 0;

  string = string.trim();
  if (string.substring(0, 1) === '(' && (string.substring(string.length - 1) === ')' || string.substring(string.length - 2) === ');')) {
    string = string.substring(1);
    string = string.substring(string.length - 1) === ')' ? string.substring(0, string.length - 1) : string.substring(0, string.length - 2);
  }

  // Validate structures.
  for (let i = 0; i < string.length; i++) {
    if (string[i] === '(') {
      braces++;
    } else if (string[i] === ')') {
      braces--;
    } else if (string[i] === "'") {
      quotes++;
    }
  }

  if (braces !== 0) {
    throw new Error('Missing paired parenthesis.');
  }

  if (quotes % 2 !== 0) {
    throw new Error('Missing closing quotes.');
  }

  let token = '';
  for (let i = 0; i < string.length; i++) {
    if (string[i] === '(') {
      braces++;
    } else if (string[i] === ')') {
      braces--;
    } else if (string[i] === "'") {
      quotes++;
    } else if (quotes > 0) {
      if (quotes % 2 === 1) {
        if (braces < maxLevel) {
          token += string[i];
        }
      } else if (braces < maxLevel) {
        tokens.push(token);
        token = '';
        quotes = 0;
      }
    }
  }

  return tokens.filter((value) => value || false);
}

function getSassVariablesFromValue(value) {
  let vars = [];

  if (value.indexOf('(') !== -1) {
    vars = getSassVariablesFromValueMap(value);
  } else {
    const re = new RegExp('\'[a-z-_0-9]+\'', 'gi');

    let match = re.exec(value);
    while (match !== null) {
      vars.push(match[0].replace(/[':]/gi, ''));
      match = re.exec(value);
    }
  }

  return vars;
}

function getVariablesFromFile(file) {
  const allValues = {};

  let importedFile = fs.readFileSync(file, { encoding: 'utf8' });

  importedFile = removeComments(importedFile);

  const values = importedFile.split(/;(?=(?:[^"']*['"][^"']*['"])*[^"']*$)/gi);

  // Extract variables from every value expression.
  values.forEach((value) => {
    const re = new RegExp('(\\$[a-z-_][a-z-_0-9]+:)', 'gim');
    const match = re.exec(value);
    if (match) {
      const name = match[0].replace(/[$:]/gi, '');
      let valueStripped = value;
      valueStripped = valueStripped.trim();
      // Remove name.
      valueStripped = valueStripped.replace(/\$[a-z-]+:/gi, '');
      // Remove new lines and double spaces.
      valueStripped = valueStripped.replace(/[\n\r]/gi, '');
      valueStripped = valueStripped.replace(/[\s]{2,}/gi, ' ');
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
