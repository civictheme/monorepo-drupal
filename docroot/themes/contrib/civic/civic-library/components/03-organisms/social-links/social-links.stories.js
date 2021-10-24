import {
  boolean, object, radios, text,
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
      title: 'facebook',
    },
    {
      symbol: 'brands_twitter',
      size: 'regular',
      url: 'www.facebook.com',
      title: 'twitter',
    },
    {
      symbol: 'brands_linkedin',
      size: 'regular',
      url: 'www.facebook.com',
      title: 'Linkedin',
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
    with_border: boolean('With border', true, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  return CivicSocialLinks({
    ...generalKnobs,
  });
};
