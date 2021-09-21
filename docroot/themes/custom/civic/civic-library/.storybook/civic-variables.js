// Extracts an object with all mapping variables from components/variables.scss.
const fs = require('fs');

const importedFile = fs.readFileSync('../components/variables.scss', { encoding:'utf8' })

function getSassVariableGroups () {
  const reg = new RegExp('\\$[a-z-]+:\\s\\(([\\s\\S\\r])+?\\;', 'gi')
  const groups = []

  // Match
  let lastMatch = reg.exec(importedFile)
  while (lastMatch !== null) {
    groups.push(lastMatch[0])
    lastMatch = reg.exec(importedFile)
  }
  return groups
}

function getSassVariablesFromGroup (group) {
  const reg = new RegExp('\'[a-z-_0-9]+\'', 'gi')
  const returnVars = []

  // Match
  let match = reg.exec(group)
  while (match !== null) {
    returnVars.push(match[0].replace(/[\':]/gi, ''))
    match = reg.exec(group)
  }
  return returnVars
}

function getGroups () {
  const allGroups = {}
  const groups = getSassVariableGroups()
  groups.forEach(group => {
    const r = new RegExp('(\\$[a-z-]+\\:)', 'gim')
    let match = r.exec(group)
    if (match) {
      let name = match[0].replace(/[\$\:]/gi, '')
      const strippedGroup = group.replace(/\$[a-z-]+\:/gi, '').replace(/\(.+?\)/gi, '').replace(/:\s\([\s\S\n\r]+?\)/gi, '')
      allGroups[name] = getSassVariablesFromGroup(strippedGroup)
    }
  })
  return allGroups
}

module.exports = {
  getVariables () {
    return getGroups()
  }
}
