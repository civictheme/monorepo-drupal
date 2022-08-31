// phpcs:ignoreFile
import { text, radios, select } from '@storybook/addon-knobs';
import CivicThemeTooltip from './tooltip.twig';

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
    symbol: select('Symbol', Object.values(ICONS), Object.values(ICONS)[0], generalKnobTab),
    size: radios('Size', sizes, sizes[2], generalKnobTab),
    title: text('Title', 'Toggle tooltip display', generalKnobTab),
    text: text('Tooltip', 'Lorem ipsum deserunt laborum commodo cillum pariatur elit excepteur laboris exercitation est dolore culpa aute dolor ullamco amet exercitation anim nostrud magna ut in tempor sunt pariatur minim in ex est nulla aliqua minim qui ea.', generalKnobTab),
  };

  return CivicThemeTooltip({
    ...generalKnobs,
  });
};
