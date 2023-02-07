// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';
import { getSlots, randomUrl } from '../../00-base/base.utils';
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
    view_url: text('View URL', randomUrl(), generalKnobTab),
    vertical_spacing: radios(
      'Vertical spacing',
      {
        None: 'none',
        Top: 'top',
        Bottom: 'bottom',
        Both: 'both',
      },
      'none',
      generalKnobTab,
    ),
    with_background: boolean('With background', false, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeMap({
    ...generalKnobs,
    ...getSlots([
      'content_top',
      'content_bottom',
    ]),
  });
};
