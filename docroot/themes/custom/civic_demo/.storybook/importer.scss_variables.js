//
// Extract CSS variables as object from SCSS file.
//
const fs = require('fs');

const importedFiles = [
  './components-combined/00-base/_variables.scss',
  './components-combined/variables.scss',
];

function removeComments(string) {
  let lines = string.split(/\r?\n/);
  lines = lines.filter((line) => line.indexOf('//') !== 0);
  return lines.join('\n');
}

function getSassVariablesFromGroup(group) {
  const vars = [];

  const re = new RegExp('\'[a-z-_0-9]+\'', 'gi');

  let match = re.exec(group);
  while (match !== null) {
    vars.push(match[0].replace(/[':]/gi, ''));
    match = re.exec(group);
  }

  return vars;
}

function getVariablesFromFile(file) {
  const allGroups = {};

  let importedFile = fs.readFileSync(file, { encoding: 'utf8' });

  importedFile = removeComments(importedFile);

  const groups = importedFile.split(/;(?=(?:[^"']*['"][^"']*['"])*[^"']*$)/gi);

  // Extract variables from every group.
  groups.forEach((group) => {
    const re = new RegExp('(\\$[a-z-]+:)', 'gim');
    const match = re.exec(group);
    if (match) {
      const name = match[0].replace(/[$:]/gi, '');
      let strippedGroup = group.replace(/\$[a-z-]+:/gi, '');
      strippedGroup = strippedGroup.replace(/\(.+?\)/gi, '');
      strippedGroup = strippedGroup.replace(/:\s\([\s\S\n\r]+?\)/gi, '');
      allGroups[name] = getSassVariablesFromGroup(strippedGroup);
    }
  });

  return allGroups;
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
