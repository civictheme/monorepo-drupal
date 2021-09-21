import { text, select, boolean } from '@storybook/addon-knobs'

import CustomExampleButton from './example-button.twig'
import './example-button.css'

export default {
  title: 'Atom/Button'
}

export const ExampleButton = () => CustomExampleButton({
  text: text('Text', 'Button Text'),
  modifier_class: select('Style', {
    'Primary': 'primary',
    'Outline': 'outline',
    'White': 'white',
    'Accent': 'accent',
    'Accent Outline': 'accent-outline'
  }, 'primary'),
  disabled: boolean('Disabled', false)
});
