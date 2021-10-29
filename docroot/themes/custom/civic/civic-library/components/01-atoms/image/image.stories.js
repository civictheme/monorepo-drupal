import { boolean, radios, text } from '@storybook/addon-knobs';

import CivicImage from './image.twig';

import imageFile from '../../../assets/image.png';

export default {
  title: 'Atoms/Image',
  parameters: {
    layout: 'centered',
  },
};

export const Image = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const generalKnobs = {
    theme: radios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      generalKnobTab,
    ),
    src: boolean('With image', true, generalKnobTab) ? imageFile : false,
    alt: text('Image alt text', 'Civic image alt', generalKnobTab),
    caption: text('Caption', 'This is a default image caption.', generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicImage({
    ...generalKnobs,
  });
};
