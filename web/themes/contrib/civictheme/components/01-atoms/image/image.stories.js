// phpcs:ignoreFile
import CivicThemeImage from './image.twig';
import { demoImage, knobBoolean, knobRadios, knobText, shouldRender } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Atoms/Image',
  parameters: {
    layout: 'centered',
  },
};

export const Image = (parentKnobs = {}) => {
  const knobs = {
    theme: knobRadios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      parentKnobs.theme,
      parentKnobs.knobTab,
    ),
    url: knobBoolean('Show image', true, parentKnobs.show_image, parentKnobs.knobTab) ? demoImage() : false,
    alt: knobText('Image alt text', 'Alternative text', parentKnobs.alt, parentKnobs.knobTab),
    width: knobText('Width', '', parentKnobs.width, parentKnobs.knobTab),
    height: knobText('Height', '', parentKnobs.height, parentKnobs.knobTab),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  return shouldRender(parentKnobs) ? CivicThemeImage(knobs) : knobs;
};
