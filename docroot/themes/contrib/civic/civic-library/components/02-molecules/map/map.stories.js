import { radios, text } from '@storybook/addon-knobs';
import { randomUrl } from '../../00-base/base.stories';
import CivicMap from './map.twig';

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
    embed_url: text('Embed URL', 'https://maps.google.com/maps?q=australia&t=&z=3&ie=UTF8&iwloc=&output=embed', generalKnobTab),
    address: text('Address', 'Australia', generalKnobTab),
    share_link: text('Share Link', randomUrl(), generalKnobTab),
    view_link: text('View Link', randomUrl(), generalKnobTab),
  };

  return CivicMap({
    ...generalKnobs,
  });
};
