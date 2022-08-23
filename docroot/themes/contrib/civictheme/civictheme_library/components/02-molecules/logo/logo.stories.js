// phpcs:ignoreFile
import { radios, text } from '@storybook/addon-knobs';
import { randomUrl } from '../../00-base/base.stories';
import CivicThemeLogo from './logo.twig';

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
    title: text('Title', 'Logo title', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
    modifier_class: `civictheme-logo-example story-wrapper-size--small ${text('Additional class', '', generalKnobTab)}`,
  };

  generalKnobs.logos = {
    mobile: {
      src: LOGOS[generalKnobs.theme].mobile,
      alt: 'Logo mobile alt text',
    },
    desktop: {
      src: LOGOS[generalKnobs.theme].desktop,
      alt: 'Logo desktop alt text',
    },
  };

  const html = CivicThemeLogo({
    ...generalKnobs,
  });

  return `${html}`;
};
