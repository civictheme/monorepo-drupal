import {
  boolean, radios, text,
} from '@storybook/addon-knobs';
import CivicSocialLinks from './social-links.twig';

export default {
  title: 'Organisms/Social Links',
  parameters: {
    layout: 'centered',
  },
};

export const SocialLinks = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const links = [
    {
      symbol: 'brands_facebook',
      size: 'regular',
      url: 'www.facebook.com',
      text: 'Facebook',
    },
    {
      symbol: 'brands_twitter',
      size: 'regular',
      url: 'www.facebook.com',
      text: 'Twitter',
    },
    {
      symbol: 'brands_linkedin',
      size: 'regular',
      url: 'www.facebook.com',
      text: 'LinkedIn',
    },
  ];

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
    links: boolean('With links', true, generalKnobTab) ? links : null,
    with_border: boolean('With border', true, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  return CivicSocialLinks({
    ...generalKnobs,
  });
};
