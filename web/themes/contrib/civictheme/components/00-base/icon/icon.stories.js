// phpcs:ignoreFile
import merge from 'deepmerge';
import CivicThemeIcon from './icon.twig';
import { arrayCombine, knobRadios, knobSelect, knobText, shouldRender, toLabels } from '../storybook/storybook.utils';

export default {
  title: 'Base/Icon',
  parameters: {
    layout: 'centered',
  },
};

export const Icon = (parentKnobs = {}) => {
  const defaultSizes = SCSS_VARIABLES['ct-icon-sizes-default'];
  const customSizes = SCSS_VARIABLES['ct-icon-sizes'];
  let sizes = Object.keys(merge(defaultSizes, customSizes));

  sizes = arrayCombine(toLabels(sizes), sizes);
  sizes = merge({ Auto: 'auto' }, sizes);

  const knobs = {
    symbol: knobSelect('Symbol', ICONS, ICONS[0], parentKnobs.symbol, parentKnobs.knobTab),
    alt: knobText('Alt', 'Icon alt text', parentKnobs.alt, parentKnobs.knobTab),
    size: knobRadios('Size', sizes, 'auto', parentKnobs.size, parentKnobs.knobTab),
    modifier_class: knobText('Additional classes', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  return shouldRender(parentKnobs) ? CivicThemeIcon(knobs) : knobs;
};
