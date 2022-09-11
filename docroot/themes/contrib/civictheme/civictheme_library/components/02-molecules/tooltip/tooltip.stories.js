// phpcs:ignoreFile
import { text, radios, select } from '@storybook/addon-knobs';
import CivicThemeTooltip from './tooltip.twig';
import './tooltip';

import '../../00-base/collapsible/collapsible';

export default {
  title: 'Molecules/Tooltip',
  parameters: {
    layout: 'centered',
  },
};

export const Tooltip = () => {
  const generalKnobTab = 'General';

  const sizes = [...new Set([
    ...SCSS_VARIABLES['civictheme-icon-sizes-default'],
    ...SCSS_VARIABLES['civictheme-icon-sizes'],
  ])];

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
    icon: select('Icon', Object.values(ICONS), Object.values(ICONS)[0], generalKnobTab),
    icon_size: radios('Icon size', sizes, sizes[2], generalKnobTab),
    title: text('Title', 'Toggle tooltip display', generalKnobTab),
    content: text('Content', 'Lorem ipsum random', generalKnobTab),
    modifier_class: text('Additional classes', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeTooltip({
    ...generalKnobs,
  });
};
