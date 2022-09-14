// phpcs:ignoreFile
import { radios, select } from '@storybook/addon-knobs';

import merge from 'deepmerge';
import CivicThemeIcon from './icon.twig';

export default {
  title: 'Atoms/Icon',
  parameters: {
    layout: 'centered',
  },
};

export const Icon = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const { icons } = ICONS;

  const defaultColors = SCSS_VARIABLES['civictheme-colors-default'];
  const customColors = SCSS_VARIABLES['civictheme-colors'];
  const colors = Object.keys(merge(defaultColors, customColors));

  const defaultSizes = SCSS_VARIABLES['civictheme-icon-sizes-default'];
  const customSizes = SCSS_VARIABLES['civictheme-icon-sizes'];
  const sizes = Object.keys(merge(defaultSizes, customSizes));

  return CivicThemeIcon({
    symbol: select('Symbol', icons, icons[0], generalKnobTab),
    color: select('Color', colors, 'primary', generalKnobTab),
    size: radios('Size', sizes, sizes[0], generalKnobTab),
  });
};

export const IconLibrary = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const { packs } = ICONS;

  const defaultColors = SCSS_VARIABLES['civictheme-colors-default'];
  const customColors = SCSS_VARIABLES['civictheme-colors'];
  const colors = Object.keys(merge(defaultColors, customColors));

  const defaultSizes = SCSS_VARIABLES['civictheme-icon-sizes-default'];
  const customSizes = SCSS_VARIABLES['civictheme-icon-sizes'];
  const sizes = Object.keys(merge(defaultSizes, customSizes));

  const selectedPack = select('Pack', Object.keys(packs), Object.keys(packs).length ? Object.keys(packs)[0] : null, generalKnobTab);

  let html = `<div class="example-container">`;

  if (selectedPack) {
    html += `<div class="example-container__title">${selectedPack.charAt(0).toUpperCase()}${selectedPack.slice(1)}</div>`;
    packs[selectedPack].forEach((icon) => {
      html += CivicThemeIcon({
        symbol: icon,
        color: select('Color', colors, 'primary', generalKnobTab),
        size: radios('Size', sizes, sizes[0], generalKnobTab),
      });
    });

    html = `<div class="story-icon-wrapper story-wrapper-size--medium">${html}</div>`;
  }

  html += '</div>';

  return html;
};
