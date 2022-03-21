import { text, radios, select } from '@storybook/addon-knobs';
import CivicTooltip from './tooltip.twig';

import '../../00-base/collapsible/collapsible';

export default {
  title: 'Molecules/Tooltip',
  parameters: {
    layout: 'centered',
  },
};

export const Tooltip = () => {
  const generalKnobTab = 'General';

  const { icons } = ICONS;
  const defaultIcon = icons.indexOf('userinterface_informationmark');
  const sizes = [...new Set([
    ...SCSS_VARIABLES['civic-icon-sizes-default'],
    ...SCSS_VARIABLES['civic-icon-sizes'],
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
    symbol: select('Symbol', icons, defaultIcon !== -1 ? icons[defaultIcon] : icons[0], generalKnobTab),
    size: radios('Size', sizes, sizes[2], generalKnobTab),
    title: text('Title', 'Toggle tooltip display', generalKnobTab),
    text: text('Tooltip', 'Lorem ipsum deserunt laborum commodo cillum pariatur elit excepteur laboris exercitation est dolore culpa aute dolor ullamco amet exercitation anim nostrud magna ut in tempor sunt pariatur minim in ex est nulla aliqua minim qui ea.', generalKnobTab),
  };

  return CivicTooltip({
    ...generalKnobs,
  });
};
