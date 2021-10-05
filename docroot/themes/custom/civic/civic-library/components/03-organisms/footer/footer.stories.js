import {
  boolean,
  radios,
  object,
  text,
} from '@storybook/addon-knobs';
import { getSlots } from '../../00-base/base.stories';
import CivicFooter from './footer.stories.twig';
import imageFile from '../../../assets/logo.png';
import './footer.scss';
import './footer';

export default {
  title: 'Organisms/Footer',
};

const middleRightMenu = [
  {
    text: 'About us',
    url: '#',
    children: [
      {
        text: 'Menu item',
        url: '#',
      },
      {
        text: 'Menu item',
        url: '#',
      },
      {
        text: 'Menu item',
        url: '#',
      },
      {
        text: 'Menu item',
        url: '#',
      },
    ],
  },
  {
    text: 'Help',
    url: '#',
    children: [
      {
        text: 'Menu item',
        url: '#',
      },
      {
        text: 'Menu item',
        url: '#',
      },
      {
        text: 'Menu item',
        url: '#',
      },
    ],
  },
  {
    text: 'Services',
    url: '#',
    children: [
      {
        text: 'External link',
        url: '#',
        new_window: true,
        is_external: true,
      },
      {
        text: 'External link',
        url: '#',
        new_window: true,
        is_external: true,
      },
      {
        text: 'External link',
        url: '#',
        new_window: true,
        is_external: true,
      },
    ],
  },
];

const middleLeftMenu = [
  {
    text: 'For individuals',
    url: '#',
  },
  {
    text: 'For businesses',
    url: '#',
  },
  {
    text: 'For government',
    url: '#',
  },
  {
    text: 'Services',
    url: '#',
  },
  {
    text: 'News & events',
    url: '#',
  },
];

const socialLinks = [
  {
    symbol: 'brands_facebook',
    size: 'link-large',
    url: 'www.facebook.com',
  },
  {
    symbol: 'brands_facebook',
    size: 'link-large',
    url: 'www.facebook.com',
  },
  {
    symbol: 'brands_facebook',
    size: 'link-large',
    url: 'www.facebook.com',
  },
];

const acknowledgement = 'We acknowledge the traditional owners of the country throughout Australia and their continuing connection to land, sea and community. We pay our respect to them and their cultures and to the elders past and present.';
const copyright = '© Commonwealth of Australia';
export const Footer = () => {
  const generalKnobTab = 'General';

  const generalKnobs = {
    theme: radios(
      'Theme', {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      generalKnobTab,
    ),
    image: boolean('With image', true, generalKnobTab) ? {
      src: imageFile,
      alt: 'Image alt text',
    } : false,
    menu_left: object('Footer menu left', middleLeftMenu, generalKnobTab),
    menu_right: object('Footer menu right', middleRightMenu, generalKnobTab),
    acknowledgement: text('Acknowledgement', acknowledgement, generalKnobTab),
    copyright: text('Copyright text', copyright, generalKnobTab),
    socialLinks: object('Social links', socialLinks, generalKnobTab),
  };

  return CivicFooter({
    ...generalKnobs,
    ...getSlots([
      'logo',
      'top_left',
      'top_right',
      'middle_left',
      'middle_right',
      'bottom_left',
      'bottom_right',
    ]),
  });
};
