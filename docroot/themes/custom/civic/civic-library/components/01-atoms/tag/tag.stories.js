import {boolean, radios, text, select} from '@storybook/addon-knobs'

import CivicTag from './tag.twig'
import './tag.scss';

// Use the icons availabe in the assets directory to compile a list of spritesheets and icon IDs.
const spritesheets = new Set()
const icons = {}
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
})

export default {
  title: 'Atom/Tag',
}


export const Tag = () => {
//Knob tab names.
  const tag = 'Tag';
  const iconList = 'Icon (Applies to tag with icon.)';

  const tagParams = {
    theme: radios(
      'Theme', {
        'Light': 'light',
        'Dark': 'dark',
      },
      'light',
      tag	
    ),
    icon: boolean('With icon', true, tag),
    icon_placement: radios(
      'Icon position', {
        'Before': 'before',
        'After': 'after',
      },
      'before',
      tag
    ),
    text: text('Text', 'Button Text', tag),
    hidden_value: text('Hidden value', 'Hidden value', tag),
    modifier_class: text('Additional class', '', tag)
  };

  //Knob tabs order is decided on the basis of their order in story.
  //Icon component parameters. 
  const sheets = Array.from(spritesheets);
  let spritesheet = select('Icon Pack', sheets, '/icons/civic-business.svg', iconList)
  let symbol = select('Symbol', icons[spritesheet], 'business-calendar', iconList)
  const colors = CIVIC_VARIABLES['civic-default-colors']

  const iconParams = {
    spritesheet,
    symbol,
    icon_color: select('Color', colors, 'primary', iconList)
  }


  return CivicTag({ ...tagParams, ...iconParams });
}
