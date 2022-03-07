import { radios, text } from '@storybook/addon-knobs';
import CivicIframe from './iframe.twig';

export default {
  title: 'Atoms/Iframe',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Iframe = (knobTab) => {
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
    url: text('URL', 'https://example.com', generalKnobTab),
    width: text('Width', '500', generalKnobTab),
    height: text('Height', '300', generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicIframe({
    ...generalKnobs,
  });
};
