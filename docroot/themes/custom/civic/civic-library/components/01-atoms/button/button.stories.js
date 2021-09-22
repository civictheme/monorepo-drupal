import {boolean, text, radios} from '@storybook/addon-knobs'

import CivicButton from './button.twig'
import './button.scss'
import './button.js'

export default {
  title: 'Atom/Button',
}

export const Button = () => CivicButton({
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
  modifier_class: [
    radios(
      'Theme',
      {
        'Dark': 'civic-button--dark',
        'Light': 'civic-button--light'
      },
      'civic-button--light',
    ),
    radios(
      'Type',
      {
        'Primary': 'civic-button--primary',
        'Primary Accent': 'civic-button--primary-accent',
        'Secondary': 'civic-button--secondary',
        'Secondary Accent': 'civic-button--secondary-accent',
      },
      'civic-button--primary',
    ),
    radios(
      'Size',
      {
        'Large': 'civic-button--large',
        'Normal': 'civic-button--normal',
        'Small': 'civic-button--small',
      },
      'civic-button--normal',
    )
  ].join(' '),
  text: text('Text', 'Button Text'),
  url: text('URL (applies to button kind link.)', ''),
  new_window: boolean('Open in a new window (applies to button kind link.)', false),
  disabled: boolean('Disabled', false),
})





