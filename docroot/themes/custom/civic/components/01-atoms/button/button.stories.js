import { text, select, boolean } from '@storybook/addon-knobs'

import CivicButton from './button.twig'
import './button.css'

export default {
  title: 'Atom/Button',
}

export const Button = () =>
  CivicButton({
    text: text('Text', 'Button Text'),
    modifier_class: select(
      'Style',
      {
        Primary: 'primary',
        Outline: 'outline',
        White: 'white',
        Accent: 'accent',
        'Accent Outline': 'accent-outline',
      },
      'primary',
    ),
    disabled: boolean('Disabled', false),
  })
