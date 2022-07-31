// phpcs:disable Generic.PHP.UpperCaseConstant.Found
// phpcs:disable Squiz.WhiteSpace.OperatorSpacing
import { boolean, radios, text } from '@storybook/addon-knobs';

import CivicThemeImage from './image.twig';
import { demoImage } from '../../00-base/base.stories';

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
    src: boolean('Show image', true, generalKnobTab) ? demoImage() : false,
    alt: text('Image alt text', 'Alternative text', generalKnobTab),
    width: text('Width', '', generalKnobTab),
    height: text('Height', '', generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeImage({
    ...generalKnobs,
  });
};
