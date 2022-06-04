import { radios, text } from '@storybook/addon-knobs';
import CivicThemeNavigation from './navigation.twig';
import getMenuLinks from '../../00-base/menu/menu.utils';

export default {
  title: 'Organisms/Navigation/Navigation',
  parameters: {
    layout: 'centered',
  },
};

export const Navigation = (knobTab) => {
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
    title: text('Title', 'Navigation title', generalKnobTab),
    items: getMenuLinks('Links'),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeNavigation({
    ...generalKnobs,
  });
};
