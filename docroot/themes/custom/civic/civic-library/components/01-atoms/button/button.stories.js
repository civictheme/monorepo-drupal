import { boolean, radios, select, text } from '@storybook/addon-knobs'

import CivicButton from './button.twig'
import './button.scss'

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
});

export default {
  title: 'Atom/Button',
}

export const Button = () => {
  const buttonKnobTab = 'Button';
  const iconKnobTab = 'Icon';

  const buttonKnobs = {
    theme: radios(
      'Theme', {
        'Light': 'light',
        'Dark': 'dark',
      },
      'light',
      buttonKnobTab
    ),
    kind: radios(
      'Kind', {
        'Button': 'button',
        'Link': 'link',
        'Reset': 'reset',
        'Submit': 'submit'
      },
      'button',
      buttonKnobTab
    ),
    type: radios(
      'Type', {
        'Primary': 'primary',
        'Secondary': 'secondary',
        'Tertiary': 'tertiary'
      },
      'primary',
      buttonKnobTab
    ),
    size: radios(
      'Size', {
        'Large': 'large',
        'Regular': 'regular',
        'Small': 'small',
      },
      'regular',
      buttonKnobTab
    ),
    text: text('Text', 'Button Text', buttonKnobTab),
    url: text('URL (applies to button kind "link")', 'http://example.com', buttonKnobTab),
    new_window: boolean('Open in a new window (applies to button kind "link")', false, buttonKnobTab),
    disabled: boolean('Disabled', false, buttonKnobTab),
    modifier_class: text('Additional class', '', buttonKnobTab),
  }

  // Icon component parameters.
  const sheets = Array.from(spritesheets)
  let spritesheet = select('Icon Pack', sheets, '/icons/civic-arrows.svg', iconKnobTab)
  let symbol = select('Symbol', icons[spritesheet], 'arrows-right-arrow-3', iconKnobTab)

  const iconKnobs = {
    icon: boolean('With icon', false, iconKnobTab),
    icon_placement: radios(
      'Icon position', {
        'Left': 'left',
        'Right': 'right',
      },
      'right',
      iconKnobTab
    ),
    spritesheet: spritesheet,
    symbol: symbol,
  }

  return CivicButton({...buttonKnobs, ...iconKnobs});
}
