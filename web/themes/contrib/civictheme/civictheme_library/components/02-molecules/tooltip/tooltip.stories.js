// phpcs:ignoreFile
import { text, radios, select } from '@storybook/addon-knobs';
import merge from 'deepmerge';
import CivicThemeTooltip from './tooltip.twig';
import './tooltip';

import '../../00-base/collapsible/collapsible';
import { randomText } from '../../00-base/base.utils';

export default {
  title: 'Molecules/Tooltip',
  parameters: {
    layout: 'centered',
  },
};

export const Tooltip = () => {
  const generalKnobTab = 'General';

  const defaultSizes = SCSS_VARIABLES['ct-icon-sizes-default'];
  const customSizes = SCSS_VARIABLES['ct-icon-sizes'];
  const sizes = Object.keys(merge(defaultSizes, customSizes));

  const generalKnobs = {
    theme: radios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      generalKnobTab,
    ),
    position: radios(
      'Position',
      {
        Auto: 'auto',
        Left: 'left',
        Right: 'right',
        Top: 'top',
        Bottom: 'bottom',
      },
      'auto',
      generalKnobTab,
    ),
    icon: select('Icon', Object.values(ICONS), 'information-mark', generalKnobTab),
    icon_size: radios('Icon size', sizes, sizes[2], generalKnobTab),
    title: text('Title', 'Toggle tooltip display', generalKnobTab),
    content: text('Content', randomText(), generalKnobTab),
    modifier_class: text('Additional classes', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeTooltip({
    ...generalKnobs,
  });
};
