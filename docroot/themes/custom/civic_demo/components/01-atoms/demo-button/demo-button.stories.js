import { boolean, radios, text } from '@storybook/addon-knobs'

import CustomDemoButton from './demo-button.twig'
import './demo-button.scss'
import './demo-button.js'

export default {
  title: 'Atom/Demo Button',
}

export const DemoButton = () => CustomDemoButton({
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
