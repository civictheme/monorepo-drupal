import { radios, text } from '@storybook/addon-knobs';
import CivicMenu from './menu.twig';
import getMenuLinks from './menu.utils';

export default {
  title: 'Base/Menu',
  parameters: {
    layout: 'centered',
  },
};

export const Menu = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

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
    items: getMenuLinks(),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  return CivicMenu({
    ...generalKnobs,
  });
};
