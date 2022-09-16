// phpcs:ignoreFile

const merge = require('deepmerge');
import CivicThemeTypography from './typography.stories.twig';

export default {
  title: 'Base/Typography',
};

export const Typography = () => {
  const defaultValues = SCSS_VARIABLES['civictheme-typography-default'];
  const customValues = SCSS_VARIABLES['civictheme-typography'];
  const typographyNames = Object.keys(merge(defaultValues, customValues));

  let html = '';

  for (const i in Object.keys(typographyNames)) {
    html += `<div class="example-container">`;
    html += `<div class="example-container__title">${typographyNames[i].charAt(0).toUpperCase()}${typographyNames[i].replace('-', ' ').slice(1)} <code>${typographyNames[i]}</code></div>`;
    html += `<div class="example-container__content civictheme-${typographyNames[i]}">The quick brown fox jumps over the lazy dog</div>`;
    html += `</div>`;
  }

  return CivicThemeTypography({
    html,
  });
};
