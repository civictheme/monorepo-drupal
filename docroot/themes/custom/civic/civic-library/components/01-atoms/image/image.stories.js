import { boolean, radios, text } from '@storybook/addon-knobs';

import CivicImage from './image.twig';

import imageFile from '../../../assets/image.png';

export default {
  title: 'Atom/Image',
  parameters: {
    layout: 'centered',
  },
};

export const Image = () => CivicImage({
  theme: radios(
    'Theme',
    {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
  ),
  src: boolean('With image', true) ? imageFile : false,
  alt: text('Image alt text', 'Civic image alt'),
  caption: text('Caption', 'This is a default image caption.'),
});
