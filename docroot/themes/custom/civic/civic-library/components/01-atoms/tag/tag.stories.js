import { boolean, radios, select, text } from '@storybook/addon-knobs'

import CivicTag from './tag.twig'
import './tag.scss';

// @todo Find a way to make this reusable.
const spritesheets = new Set()
const icons = {}
// Use the icons available in the assets directory to compile a list of spritesheets and icon IDs.
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
  const tagKnobTab = 'Tag';
  const iconKnobTab = 'Icon';

  const tagParams = {
    theme: radios(
      'Theme', {
        'Light': 'light',
        'Dark': 'dark',
      },
      'light',
      tagKnobTab
    ),
    text: text('Text', 'Button Text', tagKnobTab),
    hidden_value: text('Hidden value', 'Hidden value', tagKnobTab),
    modifier_class: text('Additional class', '', tagKnobTab)
  };

  const sheets = Array.from(spritesheets);
  let spritesheet = select('Icon Pack', sheets, '/icons/civic-business.svg', iconKnobTab)
  let symbol = select('Symbol', icons[spritesheet], 'business-calendar', iconKnobTab)
  const colors = CIVIC_VARIABLES['civic-default-colors']

  const iconParams = {
    icon: boolean('With icon', false, iconKnobTab),
    icon_placement: radios(
      'Icon position', {
        'Before': 'before',
        'After': 'after',
      },
      'before',
      iconKnobTab
    ),
    spritesheet,
    symbol,
    icon_color: select('Color', colors, 'primary', iconKnobTab)
  }

  return CivicTag({...tagParams, ...iconParams});
}
