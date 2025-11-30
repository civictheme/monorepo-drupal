// phpcs:ignoreFile
import merge from 'deepmerge';
import CivicThemeTooltip from './tooltip.twig';
import './tooltip';

import '../../00-base/collapsible/collapsible';
import { knobRadios, knobSelect, knobText, randomText, shouldRender } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Molecules/Tooltip',
  parameters: {
    layout: 'centered',
  },
};

export const Tooltip = (parentKnobs = {}) => {
  const defaultSizes = SCSS_VARIABLES['ct-icon-sizes-default'];
  const customSizes = SCSS_VARIABLES['ct-icon-sizes'];
  const sizes = Object.keys(merge(defaultSizes, customSizes));

  const knobs = {
    theme: knobRadios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      parentKnobs.theme,
      parentKnobs.knobTab,
    ),
    position: knobRadios(
      'Position',
      {
        Auto: 'auto',
        Left: 'left',
        Right: 'right',
        Top: 'top',
        Bottom: 'bottom',
      },
      'auto',
      parentKnobs.position,
      parentKnobs.knobTab,
    ),
    icon: knobSelect('Icon', Object.values(ICONS), 'information-mark', parentKnobs.icon, parentKnobs.knobTab),
    icon_size: knobRadios('Icon size', sizes, sizes[2], parentKnobs.icon_size, parentKnobs.knobTab),
    title: knobText('Title', 'Toggle tooltip display', parentKnobs.title, parentKnobs.knobTab),
    content: knobText('Content', randomText(), parentKnobs.content, parentKnobs.knobTab),
    modifier_class: knobText('Additional classes', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  return shouldRender(parentKnobs) ? CivicThemeTooltip(knobs) : knobs;
};
