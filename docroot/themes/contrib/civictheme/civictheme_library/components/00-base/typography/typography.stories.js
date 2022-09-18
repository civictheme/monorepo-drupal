// phpcs:ignoreFile

import CivicThemeTypography from './typography.stories.twig';

const merge = require('deepmerge');

export default {
  title: 'Base/Typography',
};

export const Typography = () => {
  const defaultValues = SCSS_VARIABLES['ct-typography-default'];
  const customValues = SCSS_VARIABLES['ct-typography'];
  const typographyNames = Object.keys(merge(defaultValues, customValues));

  let html = '';

  for (const i in Object.keys(typographyNames)) {
    html += `<div class="example-container">`;
    html += `<div class="example-container__title">${typographyNames[i].charAt(0).toUpperCase()}${typographyNames[i].replace('-', ' ').slice(1)} <code>${typographyNames[i]}</code></div>`;
    html += `<div class="example-container__content ct-${typographyNames[i]}">The quick brown fox jumps over the lazy dog</div>`;
    html += `</div>`;
  }

  return CivicThemeTypography({
    html,
  });
};
