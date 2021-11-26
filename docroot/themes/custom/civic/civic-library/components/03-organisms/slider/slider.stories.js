import {
  radios, text, number, boolean,
} from '@storybook/addon-knobs';
import { randomSlidesComponent, getSlots } from '../../00-base/base.stories';
import CivicSlider from './slider.twig';
import CivicButton from '../../01-atoms/button/button.twig';

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

  const numOfElements = number(
    'Number of slides',
    5, {
      range: true,
      min: 0,
      max: 10,
      step: 1,
    },
    slidesKnobTab,
  );

  const slides = randomSlidesComponent(numOfElements, theme, true, {
    ...getSlots([
      'content_top',
      'links',
      'content_bottom',
    ]),
  }).join(' ');

  const generalKnobs = {
    theme,
    title: text('Title', 'Slider title', generalKnobTab),
    link: boolean('Show link', true, generalKnobTab) ? CivicButton({
      theme,
      text: 'Slider link',
      type: 'secondary',
      kind: 'link',
      url: 'https://www.salsadigital.com.au',
    }) : null,
    slides,
  };

  return CivicSlider({
    ...generalKnobs,
  });
};
