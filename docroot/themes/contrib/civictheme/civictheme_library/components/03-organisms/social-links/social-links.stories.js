import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicSocialLinks from './social-links.twig';
import { demoIcon } from '../../00-base/base.stories';
import CivicIcon from '../../01-atoms/icon/icon.twig';

export default {
  title: 'Organisms/Social Links',
  parameters: {
    layout: 'centered',
  },
};

export const SocialLinks = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const items = [
    {
      symbol: 'brands_facebook',
      url: 'https://www.facebook.com',
      title: 'Facebook',
    },
    {
      symbol: 'brands_twitter',
      url: 'https://www.facebook.com',
      title: 'Twitter',
    },
    {
      symbol: 'brands_linkedin',
      url: 'https://www.facebook.com',
      title: 'LinkedIn',
    },
    {
      url: 'https://www.dropbox.com',
      text: `<img class="civictheme-button__icon" width=24 height=24 src="${demoIcon()}"/>`,
      title: 'Icon with inline image',
    },
    {
      url: 'https://www.youtube.com',
      text: CivicIcon({ symbol: 'brands_youtube', size: 'regular' }),
      title: 'Icon with inline SVG',
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
    items: boolean('With items', true, generalKnobTab) ? items : null,
    with_border: boolean('With border', true, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  return CivicSocialLinks({
    ...generalKnobs,
  });
};
