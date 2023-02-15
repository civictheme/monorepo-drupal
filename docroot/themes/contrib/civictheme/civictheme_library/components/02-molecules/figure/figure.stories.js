// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';

import CivicThemeFigure from './figure.twig';
import { demoImage } from '../../00-base/base.utils';

export default {
  title: 'Molecules/Figure',
  parameters: {
    layout: 'centered',
  },
};

export const Figure = (knobTab) => {
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
    url: boolean('With image', true, generalKnobTab) ? demoImage() : false,
    alt: text('Image alt text', 'Alternative text', generalKnobTab),
    width: text('Width', '600', generalKnobTab),
    height: text('Height', '', generalKnobTab),
    caption: text('Caption', 'Figure image caption.', generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeFigure({
    ...generalKnobs,
  });
};
