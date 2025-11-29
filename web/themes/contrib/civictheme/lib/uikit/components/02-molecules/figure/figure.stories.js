// phpcs:ignoreFile
import CivicThemeFigure from './figure.twig';
import { demoImage, knobBoolean, knobRadios, knobText, shouldRender } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Molecules/Figure',
  parameters: {
    layout: 'centered',
  },
};

export const Figure = (parentKnobs = {}) => {
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
    url: knobBoolean('With image', true, parentKnobs.with_image, parentKnobs.knobTab) ? demoImage() : false,
    alt: knobText('Image alt text', 'Alternative text', parentKnobs.alt, parentKnobs.knobTab),
    width: knobText('Width', '600', parentKnobs.width, parentKnobs.knobTab),
    height: knobText('Height', '', parentKnobs.height, parentKnobs.knobTab),
    caption: knobText('Caption', 'Figure image caption.', parentKnobs.caption, parentKnobs.knobTab),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  return shouldRender(parentKnobs) ? CivicThemeFigure(knobs) : knobs;
};
