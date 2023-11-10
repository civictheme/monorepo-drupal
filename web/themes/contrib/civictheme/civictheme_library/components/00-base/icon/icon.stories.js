// phpcs:ignoreFile
import { radios, select, text } from '@storybook/addon-knobs';

import merge from 'deepmerge';
import CivicThemeIcon from './icon.twig';
import { arrayCombine, toLabels } from '../base.utils';

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
  let sizes = Object.keys(merge(defaultSizes, customSizes));

  sizes = arrayCombine(toLabels(sizes), sizes);
  sizes = merge({ Auto: 'auto' }, sizes);

  return CivicThemeIcon({
    symbol: select('Symbol', ICONS, ICONS[0], generalKnobTab),
    alt: text('Alt', 'Icon alt text', generalKnobTab),
    size: radios('Size', sizes, 'auto', generalKnobTab),
    modifier_class: text('Additional classes', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  });
};
