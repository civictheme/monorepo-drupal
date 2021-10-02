import { radios, text } from '@storybook/addon-knobs'

import CivicImage from './image.twig'
import './image.scss'

import imageFile from '../../../assets/image.png';

export default {
  title: 'Atom/Image',
}

export const Image = () => CivicImage({
  theme: radios(
    'Theme',
    {
      'Light': 'light',
      'Dark': 'dark',
    },
    'light',
  ),
  src: text('Image path', imageFile),
  alt: text('Image alt text', 'Civic image alt'),
  caption: text('Caption', 'This is a default image caption.'),
})
