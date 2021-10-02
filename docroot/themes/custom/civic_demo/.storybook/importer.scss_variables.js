//
// Extract CSS variables as object from SCSS file.
//
const fs = require('fs');

const importedFiles = [
  './components-combined/00-base/_variables.scss',
  './components-combined/variables.scss',
];

function getVariables() {
  let variables = {}
  for (var i in importedFiles) {
    variables = {...variables, ...getVariablesFromFile(importedFiles[i])};
  }
  return variables
}

function getVariablesFromFile(file) {
  const importedFile = fs.readFileSync(file, {encoding: 'utf8'})

  const allGroups = {}

  // Get all variable groups.
  const groups = []
  const re = new RegExp('\\$[a-z-]+:\\s\\(([\\s\\S\\r])+?\\;', 'gi')
  let lastMatch = re.exec(importedFile)
  while (lastMatch !== null) {
    groups.push(lastMatch[0])
    lastMatch = re.exec(importedFile)
  }

  // Extract variables from every group.
  groups.forEach(group => {
    const re = new RegExp('(\\$[a-z-]+\\:)', 'gim')
    let match = re.exec(group)
    if (match) {
      let name = match[0].replace(/[\$\:]/gi, '')
      const strippedGroup = group.replace(/\$[a-z-]+\:/gi, '').replace(/\(.+?\)/gi, '').replace(/:\s\([\s\S\n\r]+?\)/gi, '')
      allGroups[name] = getSassVariablesFromGroup(strippedGroup)
    }
  })

  return allGroups
}

function getSassVariablesFromGroup(group) {
  const vars = []

  const re = new RegExp('\'[a-z-_0-9]+\'', 'gi')

  let match = re.exec(group)
  while (match !== null) {
    vars.push(match[0].replace(/[\':]/gi, ''))
    match = re.exec(group)
  }

  return vars
}

module.exports = {
  getVariables,
}
