// phpcs:ignoreFile
import { radios, select, text } from '@storybook/addon-knobs';

import CivicThemeIcon from './icon.twig';

export default {
  title: 'Base/Icon',
  parameters: {
    layout: 'centered',
  },
};

export const Icon = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const sizes = [...new Set([
    ...SCSS_VARIABLES['civictheme-icon-sizes-default'],
    ...SCSS_VARIABLES['civictheme-icon-sizes'],
  ])];

  return CivicThemeIcon({
    symbol: select('Symbol', Object.values(ICONS), Object.values(ICONS)[0], generalKnobTab),
    size: radios('Size', sizes, sizes[2], generalKnobTab),
    alt: text('Icon alt text', 'Alternative text', generalKnobTab),
  });
};
