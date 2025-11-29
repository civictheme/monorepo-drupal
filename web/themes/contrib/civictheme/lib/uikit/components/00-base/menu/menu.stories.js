// phpcs:ignoreFile
import { radios, text } from '@storybook/addon-knobs';
import CivicThemeMenu from './menu.twig';
import getMenuLinks from './menu.utils';

export default {
  title: 'Base/Menu Generator',
  parameters: {
    layout: 'centered',
  },
};

export const MenuGenerator = (knobTab) => {
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

  return CivicThemeMenu({
    ...generalKnobs,
  });
};
