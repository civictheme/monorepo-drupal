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
    url: text('Link', randomUrl(), generalKnobTab),
    title: text('Title', 'Logo title', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
    modifier_class: `civictheme-logo-example story-wrapper-size--small ${text('Additional class', '', generalKnobTab)}`,
  };

  generalKnobs.logos = generalKnobs.with_secondary_image ? {
    mobile: {
      primary: {
        src: LOGOS[generalKnobs.theme].mobile.primary,
        alt: 'Primary logo mobile alt text',
      },
      secondary: {
        src: LOGOS[generalKnobs.theme].mobile.secondary,
        alt: 'Secondary logo mobile alt text',
      },
    },
    desktop: {
      primary: {
        src: LOGOS[generalKnobs.theme].desktop.primary,
        alt: 'Primary logo desktop alt text',
      },
      secondary: {
        src: LOGOS[generalKnobs.theme].desktop.secondary,
        alt: 'Secondary logo desktop alt text',
      },
    },
  } : {
    mobile: {
      primary: {
        src: LOGOS[generalKnobs.theme].mobile.primary,
        alt: 'Primary logo mobile alt text',
      },
    },
    desktop: {
      primary: {
        src: LOGOS[generalKnobs.theme].desktop.primary,
        alt: 'Primary logo desktop alt text',
      },
    },
  };

  const html = CivicThemeLogo({
    ...generalKnobs,
  });

  return `${html}`;
};
