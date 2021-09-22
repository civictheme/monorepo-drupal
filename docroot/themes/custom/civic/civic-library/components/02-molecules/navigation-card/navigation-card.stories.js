import { text, boolean, radios, select, object } from '@storybook/addon-knobs'
import { spritesheets, icons } from '../../01-atoms/icon/icon-helper'

import CivicNavigationCard from './navigation-card.twig'
import './navigation-card.scss'

export default {
  title: 'Molecule/Cards'
}


export const NavigationCard = () => {

  // Knob tab names.
  const navCard = 'Navigation card';
  const iconList = 'Icon (Applies to card with icon.)';

  const imageData = {
    'src': 'https://via.placeholder.com/580x580',
    'width': 580,
    'height': 580,
    'alt': 'image alt text'
  };
  
  // Current component parameters.  
  const navCardParams = {
    theme: radios('Theme', {
      'Light': 'light',
      'Dark': 'dark'
    }, 'light', navCard),
    modifier_class: [radios('Type', {
      'With image': 'civic-nav-card--large',
      'Without image': 'civic-nav-card--small',
      'With Icon': 'civic-nav-card--icon'
    }, 'civic-nav-card--large', navCard)].join(' '),
    title: text('Title', 'Navigation card heading which runs across two or three lines', navCard),
    summary: text('Summary', 'Recommend keeping card summary short over two or three lines.', navCard),
    image: object('Image  (Applies to card with image.)', imageData, navCard),
    url: text('Card URL', 'https://google.com', navCard)
  };

  // Knob tabs order is decided on the basis of their order in story.
  // Icon component parameters. 
  const sheets = Array.from(spritesheets)
  let spritesheet = select('Icon Pack', sheets, sheets[0], iconList)
  let symbol = select('Symbol', icons[spritesheet], icons[spritesheet][0], iconList)
  const colors = CIVIC_VARIABLES['civic-default-colors']

  const iconParams = { 
    spritesheet,
    symbol,
    icon_color: select('Color', colors, 'primary', iconList)
  }

  return CivicNavigationCard({ ...navCardParams, ...iconParams });
}

