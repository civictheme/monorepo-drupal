// phpcs:ignoreFile
import { radios, select } from '@storybook/addon-knobs';

import merge from 'deepmerge';
import CivicThemeIcon from './icon.twig';

export default {
  title: 'Base/Icon',
  parameters: {
    layout: 'centered',
  },
};

export const Icon = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const defaultSizes = SCSS_VARIABLES['ct-icon-sizes-default'];
  const customSizes = SCSS_VARIABLES['ct-icon-sizes'];
  const sizes = Object.keys(merge(defaultSizes, customSizes));

  return CivicThemeIcon({
    symbol: select('Symbol', ICONS, ICONS[0], generalKnobTab),
    size: radios('Size', sizes, sizes[0], generalKnobTab),
  });
};
