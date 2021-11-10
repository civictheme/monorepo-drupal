import { radios, select } from '@storybook/addon-knobs';

import CivicIcon from './icon.twig';

export default {
  title: 'Atoms/Icon',
  parameters: {
    layout: 'centered',
  },
};

export const Icon = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const { icons } = ICONS;
  const colors = [...new Set([...SCSS_VARIABLES['civic-colors-default'], ...SCSS_VARIABLES['civic-colors']])];
  const sizes = [...new Set([
    ...SCSS_VARIABLES['civic-icon-sizes-default'],
    ...SCSS_VARIABLES['civic-icon-sizes'],
  ])];

  return CivicIcon({
    symbol: select('Symbol', icons, icons[0], generalKnobTab),
    color: select('Color', colors, 'primary', generalKnobTab),
    size: radios('Size', sizes, sizes[0], generalKnobTab),
  });
};

export const IconLibrary = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const { packs } = ICONS;
  const colors = [...new Set([...SCSS_VARIABLES['civic-colors-default'], ...SCSS_VARIABLES['civic-colors']])];
  const sizes = SCSS_VARIABLES['civic-icon-sizes'];

  const selectedPack = select('Pack', Object.keys(packs), Object.keys(packs).length ? Object.keys(packs)[0] : null, generalKnobTab);

  let html = `<div class="example-container">`;

  if (selectedPack) {
    html += `<div class="example-container__title">${selectedPack.charAt(0).toUpperCase()}${selectedPack.slice(1)}</div>`;
    packs[selectedPack].forEach((icon) => {
      html += CivicIcon({
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
