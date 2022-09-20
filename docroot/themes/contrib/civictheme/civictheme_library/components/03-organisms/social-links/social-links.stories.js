// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicThemeSocialLinks from './social-links.twig';
import { demoIcon } from '../../00-base/base.stories';
import CivicThemeIcon from '../../00-base/icon/icon.twig';

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
      symbol: 'facebook',
      url: 'https://www.facebook.com',
      title: 'Facebook',
    },
    {
      symbol: 'twitter',
      url: 'https://www.facebook.com',
      title: 'Twitter',
    },
    {
      url: 'https://www.dropbox.com',
      text: `<img class="ct-button__icon" width=20 height=20 src="${demoIcon()}"/>`,
      title: 'Icon with inline image',
    },
    {
      url: 'https://www.linkedin.com',
      text: CivicThemeIcon({ symbol: 'linkedin', size: 'small' }),
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
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeSocialLinks({
    ...generalKnobs,
  });
};
