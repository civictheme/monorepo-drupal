// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicThemeSocialLinks from './social-links.twig';
import { demoIcon } from '../../00-base/base.utils';
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
      icon_html: `<img class="ct-button__icon" width=16 height=16 src="${demoIcon()}"/>`,
      url: 'https://www.dropbox.com',
      // Deliberately left without a title.
    },
    {
      title: 'Facebook',
      icon: 'facebook',
      url: 'https://www.facebook.com',
    },
    {
      title: 'Instagram',
      icon: 'instagram',
      url: 'https://www.instagram.com',
    },
    {
      title: 'Icon with inline SVG',
      // icon_html should take precedence.
      icon_html: CivicThemeIcon({ symbol: 'linkedin', size: 'small' }),
      icon: 'linkedin',
      url: 'https://www.linkedin.com',
    },
    {
      title: 'X',
      icon: 'x',
      url: 'https://www.twitter.com',
    },
    {
      title: 'YouTube',
      icon: 'youtube',
      url: 'https://www.youtube.com',
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
