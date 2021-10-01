import { radios, select, text } from '@storybook/addon-knobs'
import imageFile from '../../../assets/image.png';

import CivicNavigationCard from './navigation-card.twig'
import './navigation-card.scss'

export default {
  title: 'Molecule/Navigation Card',
  parameters: {
    layout: 'centered',
  },
}

export const NavigationCard = () => {
  const navCard = 'Navigation card';
  const iconList = 'Icon (Applies to card with icon.)';

  // Current component parameters.
  const navCardParams = {
    theme: radios('Theme', {
      'Light': 'light',
      'Dark': 'dark'
    }, 'light', navCard),
    type: [radios('Type', {
      'With image': 'large',
      'Without image': 'small',
      'With Icon': 'icon'
    }, 'large', navCard)].join(' '),
    title: text('Title', 'Navigation card heading which runs across two or three lines', navCard),
    summary: text('Summary', 'Recommend keeping card summary short over two or three lines.', navCard),
    image: {
      src: text('Image path', imageFile),
      alt: text('Image alt text', 'Civic image alt')
    },
    url: text('Card URL', 'https://google.com', navCard),
    modifier_class: text('Additional class', '', navCard),
  };

  //Knob tabs order is decided on the basis of their order in story.
  //Icon component parameters.
  const colors = CIVIC_VARIABLES['civic-default-colors']
  const icons = CIVIC_ICONS.icons

  const iconParams = {
    symbol: select('Symbol', icons, icons[0], iconList),
    icon_color: select('Color', colors, 'primary', iconList)
  }

  return CivicNavigationCard({...navCardParams, ...iconParams});
}

