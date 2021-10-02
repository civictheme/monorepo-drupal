//
// Generate icon library file from icon files.
//

const fs = require('fs');
const iconUtils = require('./icon.utils');

// Path to the output icon library file.
const iconLibraryPath = './components/01-atoms/icon/icon_library.twig';

function getMatches(reg, text, matchIndex) {
  const matches = [];
  let match = reg.exec(text);
  while (match !== null) {
    matches.push(matchIndex ? match[matchIndex] : match);
    match = reg.exec(text);
  }
  return matches;
}

function renderSvgPath(path) {
  return Object.keys(path).map((key) => `"${key}": "${path[key]}"`).join(', ');
}

function renderTwigSet(name, vars) {
  return `{% set ${name} = {\n  ${vars.join(',\n  ')}\n} %}\n{% block content %}{% endblock %}`;
}

// Extract the name, width and path out of them.
const twigVariables = [];
iconUtils.getIconPaths().forEach((path) => {
  const iconContent = fs.readFileSync(path, 'utf-8');

  const reWidth = new RegExp('width="([0-9]+)"', 'gi');
  const reHeight = new RegExp('height="([0-9]+)"', 'gi');
  const rePath = new RegExp('<path ([^\\/]+?)\\/>', 'gi');
  const reAttr = new RegExp('([a-zA-Z\\-]+)="(.+?)"', 'gi');

  const iconPathsContent = getMatches(rePath, iconContent, 1);

  // Parse the contents of the <path> tag into array of paths objects with
  // attributes.
  const iconPaths = [];
  iconPathsContent.forEach((html) => {
    const pathProps = {};
    getMatches(reAttr, html, null).forEach((match) => {
      // eslint-disable-next-line prefer-destructuring
      pathProps[match[1]] = match[2];
    });
    iconPaths.push(pathProps);
  });

  const paths = `[ ${iconPaths.map((p) => `{${renderSvgPath(p)}}`)} ]`;
  const width = getMatches(reWidth, iconContent, 1)[0];
  const height = getMatches(reHeight, iconContent, 1)[0];
  const name = iconUtils.getIconNameFromPath(path);

  twigVariables.push(`"${name}": { "width": ${width}, "height": ${height}, "paths": ${paths} }`);
});

// Save to an icon file.
fs.writeFileSync(iconLibraryPath, renderTwigSet('icons', twigVariables));
