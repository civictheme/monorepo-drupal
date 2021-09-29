//
// Extract CSS variables as object from SCSS file.
//
const fs = require('fs');

const importedFile = fs.readFileSync('./components/00-base/_variables.scss', { encoding:'utf8' })

function getGroups () {
  const allGroups = {}

  const groups = getSassVariableGroups()

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

function getSassVariableGroups () {
  const groups = []

  const re = new RegExp('\\$[a-z-]+:\\s\\(([\\s\\S\\r])+?\\;', 'gi')

  let lastMatch = re.exec(importedFile)
  while (lastMatch !== null) {
    groups.push(lastMatch[0])
    lastMatch = re.exec(importedFile)
  }

  return groups
}

function getSassVariablesFromGroup (group) {
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
  getVariables () {
    return getGroups()
  }
}
