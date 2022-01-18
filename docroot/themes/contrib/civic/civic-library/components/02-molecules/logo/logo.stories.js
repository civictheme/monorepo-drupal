import { radios, text } from '@storybook/addon-knobs';
import { randomUrl } from '../../00-base/base.stories';
import CivicLogo from './logo.twig';

export default {
  title: 'Molecules/Logo',
  parameters: {
    layout: 'centered',
  },
};

export const Logo = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const generalKnobs = {
    theme: radios('Theme', {
      Light: 'light',
      Dark: 'dark',
    }, 'light', generalKnobTab),
    logos: {},
    url: text('Link', randomUrl(), generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  generalKnobs.logos = {
    mobile: {
      src: LOGOS.mobile[generalKnobs.theme],
      alt: 'Logo mobile alt text',
    },
    desktop: {
      src: LOGOS.desktop[generalKnobs.theme],
      alt: 'Logo desktop alt text',
    },
  };

  const html = CivicLogo({
    ...generalKnobs,
  });

  return `<div class="civic-logo-example story-wrapper-size--small">${html}</div>`;
};
