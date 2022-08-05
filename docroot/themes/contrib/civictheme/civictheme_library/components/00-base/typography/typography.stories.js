// phpcs:ignoreFile
export default {
  title: 'Base/Typography',
};

export const Typography = () => {
  const typographyNames = [...new Set([
    ...SCSS_VARIABLES['civictheme-typography-default'],
    ...SCSS_VARIABLES['civictheme-typography'],
  ])];

  let html = '';

  for (const i in Object.values(typographyNames)) {
    html += `<div class="example-container">`;
    html += `<div class="example-container__title">${typographyNames[i].charAt(0).toUpperCase()}${typographyNames[i].replace('-', ' ').slice(1)} <code>${typographyNames[i]}</code></div>`;
    html += `<div class="example-container__content civictheme-${typographyNames[i]}">The quick brown fox jumps over the lazy dog</div>`;
    html += `</div>`;
  }

  return html;
};
