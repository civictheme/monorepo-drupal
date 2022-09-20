// phpcs:ignoreFile
import { radios, text } from '@storybook/addon-knobs';
import CivicThemeSecondaryNavigation from './secondary-navigation.twig';
import getMenuLinks from '../../00-base/menu/menu.utils';

export default {
  title: 'Organisms/Navigation/Secondary Navigation',
  parameters: {
    layout: 'centered',
  },
};

export const SecondaryNavigation = (knobTab) => {
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

  return CivicThemeSecondaryNavigation({
    ...generalKnobs,
  });
};
