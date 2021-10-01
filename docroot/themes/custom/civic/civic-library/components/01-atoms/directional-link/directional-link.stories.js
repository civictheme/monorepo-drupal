import { boolean, radios, text } from '@storybook/addon-knobs'
import CivicDirectionalLink from './directional-link.twig'
import './directional-link.scss'

export default {
  title: 'Atom/Directional Link',
  parameters: {
    layout: 'centered',
  },
}

export const DirectionalLink = () => {
  return CivicDirectionalLink({
    theme: radios(
      'Theme', {
        'Light': 'light',
        'Dark': 'dark',
      },
      'light',
    ),
    direction: radios(
      'Direction', {
        'Top': 'top',
        'Next': 'next',
        'Bottom': 'bottom',
        'Back': 'back',
      },
      'next'
    ),
    text: text('Text', 'Top'),
    url: text('URL', '#top'),
    is_disabled: boolean('Is disabled', false),
    modifier_class: text('Additional class', ''),
  })
};
