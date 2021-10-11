import {
  radios,
  object,
} from '@storybook/addon-knobs';
import CivicSocialLinks from './social-links.twig';
import './social-links.scss';

export default {
  title: 'Organisms/Social Links',
  parameters: {
    layout: 'centered',
  },
};

export const SocialLinks = () => {

  const socialLinks = [
    {
      symbol: 'brands_facebook',
      size: 'regular',
      url: 'www.facebook.com',
    },
    {
      symbol: 'brands_twitter',
      size: 'regular',
      url: 'www.facebook.com',
    },
    {
      symbol: 'brands_linkedin',
      size: 'regular',
      url: 'www.facebook.com',
    },
  ];

  const generalKnobTab = 'General';

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
    links: object('Links', socialLinks, generalKnobTab),
  };

  return CivicSocialLinks({
    ...generalKnobs,
    with_border: true,
  });
};
