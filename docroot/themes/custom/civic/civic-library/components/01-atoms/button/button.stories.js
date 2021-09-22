import {boolean, radios, text} from '@storybook/addon-knobs'

import CivicButton from './button.twig'
import './button.scss'
import './button.js'

export default {
  title: 'Atom/Button',
}

export const Button = () => CivicButton({
  theme: radios(
    'Theme',
    {
      'Light': 'light',
      'Dark': 'dark',
    },
    'light',
  ),
  kind: radios(
    'Kind',
    {
      'Button': 'button',
      'Link': 'link',
      'Reset': 'reset',
      'Submit': 'submit'
    },
    'button',
  ),
  type: radios(
    'Type',
    {
      'None': 'none',
      'Primary': 'primary',
      'Primary Accent': 'primary-accent',
      'Secondary': 'secondary',
      'Secondary Accent': 'secondary-accent',
    },
    'none',
  ),
  size: radios(
    'Size',
    {
      'Large': 'large',
      'Regular': 'regular',
      'Small': 'small',
    },
    'regular',
  ),
  text: text('Text', 'Button Text'),
  url: text('URL (applies to button kind link.)', 'http://example.com'),
  new_window: boolean('Open in a new window (applies to button kind link.)', false),
  disabled: boolean('Disabled', false),
  modifier_class: text('Additional class', ''),
})