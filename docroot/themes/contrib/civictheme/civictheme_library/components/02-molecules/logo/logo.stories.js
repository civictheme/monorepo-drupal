// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';
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
    type: radios('Type', {
      Default: 'default',
      Inline: 'inline',
      Stacked: 'stacked',
    }, 'default', generalKnobTab),
    with_secondary_image: boolean('With secondary image', false, generalKnobTab),
    logos: {},
    logo_secondary: {},
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

  generalKnobs.logo_secondary = {
    mobile: {
      src: SECONDARY_LOGOS[generalKnobs.theme].mobile,
      alt: 'Logo secondary mobile alt text',
    },
    desktop: {
      src: SECONDARY_LOGOS[generalKnobs.theme].desktop,
      alt: 'Logo secondary desktop alt text',
    },
  };

  const html = CivicThemeLogo({
    ...generalKnobs,
  });

  return `${html}`;
};
