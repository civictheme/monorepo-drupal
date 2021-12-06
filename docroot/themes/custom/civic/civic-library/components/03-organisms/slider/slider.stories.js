import {
  boolean, number, radios, text,
} from '@storybook/addon-knobs';
import { getSlots, randomUrl } from '../../00-base/base.stories';
import { randomSlidesComponent } from './slider.utils';
import CivicSlider from './slider.twig';

export default {
  title: 'Organisms/Slider',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Slider = () => {
  const generalKnobTab = 'General';
  const slidesKnobTab = 'Slide';
  const theme = radios(
    'Theme',
    {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
    generalKnobTab,
  );

  const numOfSlides = number(
    'Number of slides',
    5, {
      range: true,
      min: 0,
      max: 10,
      step: 1,
    },
    slidesKnobTab,
  );

  const slides = randomSlidesComponent(numOfSlides, theme, true, {
    image_position: radios('Image Position', {
      Left: 'left',
      Right: 'right',
    }, 'right', generalKnobTab),
    ...getSlots([
      'content_top',
      'links',
      'content_bottom',
    ]),
  }).join(' ');

  const generalKnobs = {
    theme,
    title: text('Title', 'Slider title', generalKnobTab),
    link: boolean('Show link', true, generalKnobTab) ? {
      type: 'secondary',
      text: 'Slider link',
      url: randomUrl(),
    } : null,
    slides,
    previous_label: text('Previous Label', 'Previous', generalKnobTab),
    next_label: text('Next Label', 'Next', generalKnobTab),
  };

  return CivicSlider({
    ...generalKnobs,
  });
};
