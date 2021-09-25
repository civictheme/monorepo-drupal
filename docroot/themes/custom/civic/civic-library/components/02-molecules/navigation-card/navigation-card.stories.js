import { text, boolean, radios, select, object } from '@storybook/addon-knobs'

import CivicNavigationCard from './navigation-card.twig'
import './navigation-card.scss'


// @todo Find a way to make this reusable.
const spritesheets = new Set()
const icons = {}
// Use the icons availabe in the assets directory to compile a list of spritesheets and icon IDs.
require.context('../../../assets/icons/', true, /\.svg$/).keys().forEach(path => {
  // Get a list of all spritesheets.
  const spritesheetName = path.substring(2, path.indexOf('/', 2)).replace(/\s/g, '-').toLowerCase()
  const spritesheetURL = `/icons/civic-${spritesheetName}.svg`
  spritesheets.add(spritesheetURL)

  // Get all icons available within the spritesheets.
  const iconName = path.substring(path.lastIndexOf('/') + 1, path.lastIndexOf('.')).toLowerCase().replace(/\s/g, '-').replace(/[^a-z0-9\-]+/, '')
  if (!icons[spritesheetURL]) {
    icons[spritesheetURL] = []
  }
  icons[spritesheetURL].push(`${spritesheetName}-${iconName}`)
});


export default {
  title: 'Molecule/Cards'
}


export const NavigationCard = () => {

  // Knob tab names.
  const navCard = 'Navigation card';
  const iconList = 'Icon (Applies to card with icon.)';

  const imageData = {
    'src': 'https://via.placeholder.com/400x400',
    'alt': 'image alt text'
  };
  
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
    image: object('Image  (Applies to card with image.)', imageData, navCard),
    url: text('Card URL', 'https://google.com', navCard),
    modifier_class: text('Additional class', '', navCard),
  };

  //Knob tabs order is decided on the basis of their order in story.
  //Icon component parameters. 
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

