import { boolean, text, radios, select } from '@storybook/addon-knobs'
import CivicDirectionalLink from './directional-link.twig'
import './directional-link.scss'

export default {
  title: 'Atom/Directional Link',
  parameters: {
    layout: 'centered',
  },
}

export const DirectionalLink = () => {
  const options = {
    'Top': 'top',
    'Next': 'next',
    'Bottom': 'bottom',
    'Back': 'back',
  }

  return CivicDirectionalLink({
    theme: radios(
      'Theme', {
        'Light': 'light',
        'Dark': 'dark',
      },
      'light',
    ),
    text: text('Text', 'Top'),
    url: text('URL', '#top'),
    direction: select('Direction', options, 'right'),
    is_disabled: boolean('Is disabled', false),
  })
};
