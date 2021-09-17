import {boolean, text, radios} from '@storybook/addon-knobs'

import CivicButton from './button.twig'
import './button.scss'
import './button.js'

export default {
  title: 'Atom/Button',
}

export const Button = () => CivicButton({
  modifier_class: [
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
  disabled: boolean('Disabled', false),
})
