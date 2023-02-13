// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';
import { randomUrl } from '../../00-base/base.utils';
import CivicThemeLogo from './logo.twig';

export default {
  title: 'Molecules/Logo',
  parameters: {
    layout: 'centered',
  },
};

export const Logo = (knobTab, doRender = true) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const generalKnobs = {
    theme: radios('Theme', {
      Light: 'light',
      Dark: 'dark',
    }, 'light', generalKnobTab),
    type: radios('Type', {
      Default: 'default',
      Stacked: 'stacked',
      Inline: 'inline',
      'Inline-Stacked': 'inline-stacked',
    }, 'default', generalKnobTab),
    with_secondary_image: boolean('With secondary image', false, generalKnobTab),
    logos: {},
    url: text('Link', randomUrl(), generalKnobTab),
    title: text('Title', 'Logo title', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  generalKnobs.logos = generalKnobs.with_secondary_image ? {
    primary: {
      mobile: {
        url: LOGOS[generalKnobs.theme].primary.mobile,
        alt: 'Primary logo mobile alt text',
      },
      desktop: {
        url: LOGOS[generalKnobs.theme].primary.desktop,
        alt: 'Primary logo desktop alt text',
      },
    },
    secondary: {
      mobile: {
        url: LOGOS[generalKnobs.theme].secondary.mobile,
        alt: 'Secondary logo mobile alt text',
      },
      desktop: {
        url: LOGOS[generalKnobs.theme].secondary.desktop,
        alt: 'Secondary logo desktop alt text',
      },
    },
  } : {
    primary: {
      mobile: {
        url: LOGOS[generalKnobs.theme].primary.mobile,
        alt: 'Primary logo mobile alt text',
      },
      desktop: {
        url: LOGOS[generalKnobs.theme].primary.desktop,
        alt: 'Primary logo mobile alt text',
      },
    },
  };

  return doRender ? CivicThemeLogo({
    ...generalKnobs,
  }) : generalKnobs;
};
