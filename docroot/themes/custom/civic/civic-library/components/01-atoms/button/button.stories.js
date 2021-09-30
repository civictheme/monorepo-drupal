import { boolean, radios, select, text } from '@storybook/addon-knobs'

import CivicButton from './button.twig'
import './button.scss'

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
  const icons = CIVIC_ICON.icons

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
    symbol: select('Symbol', icons, 'arrows_rightarrow_3', iconKnobTab),
  }

  return CivicButton({...buttonKnobs, ...iconKnobs});
}
