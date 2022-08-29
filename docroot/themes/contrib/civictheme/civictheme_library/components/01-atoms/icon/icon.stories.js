// phpcs:ignoreFile
import { radios, select } from '@storybook/addon-knobs';

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
  const sizes = [...new Set([
    ...SCSS_VARIABLES['civictheme-icon-sizes-default'],
    ...SCSS_VARIABLES['civictheme-icon-sizes'],
  ])];

  return CivicThemeIcon({
    symbol: select('Symbol', icons, icons[0], generalKnobTab),
    size: radios('Size', sizes, sizes[2], generalKnobTab),
  });
};
