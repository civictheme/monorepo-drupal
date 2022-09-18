// phpcs:ignoreFile
import { radios, text } from '@storybook/addon-knobs';
import { randomUrl } from '../../00-base/base.stories';
import CivicThemeMap from './map.twig';

export default {
  title: 'Molecules/Map',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Map = (knobTab) => {
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
    url: text('URL', 'https://maps.google.com/maps?q=australia&t=&z=3&ie=UTF8&iwloc=&output=embed', generalKnobTab),
    address: text('Address', 'Australia', generalKnobTab),
    view_link: text('View Link', randomUrl(), generalKnobTab),
    vertical_space: radios(
      'Vertical space',
      {
        None: 'none',
        Top: 'top',
        Bottom: 'bottom',
        Both: 'both',
      },
      'none',
      generalKnobTab,
    ),
  };

  return CivicThemeMap({
    ...generalKnobs,
  });
};
