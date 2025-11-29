// phpcs:ignoreFile
import { knobBoolean, knobNumber, knobRadios, knobText, randomLinks, randomTags, shouldRender, slotKnobs } from '../../00-base/storybook/storybook.utils';
import { randomSlidesComponent } from './slider.utils';
import './slider';
import CivicThemeSlider from './slider.twig';

export default {
  title: 'Organisms/Slider',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Slider = (parentKnobs = {}) => {
  const theme = knobRadios(
    'Theme',
    {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
    parentKnobs.theme,
    parentKnobs.knobTab,
  );

  const slidesKnobTab = 'Slides';
  const numOfSlides = knobNumber(
    'Number of slides',
    5,
    {
      range: true,
      min: 0,
      max: 10,
      step: 1,
    },
    parentKnobs.number_of_slides,
    slidesKnobTab,
  );

  const slides = randomSlidesComponent(numOfSlides, theme, true, {
    image_position: knobRadios('Image Position', {
      Left: 'left',
      Right: 'right',
    }, 'right', parentKnobs.image_position, slidesKnobTab),
    tags: randomTags(knobNumber(
      'Number of tags',
      2,
      {
        range: true,
        min: 0,
        max: 10,
        step: 1,
      },
      parentKnobs.number_of_tags,
      slidesKnobTab,
    ), true),
    date: knobText('Date', '20 Jan 2023 11:00', parentKnobs.date, slidesKnobTab),
    date_end: knobText('End date', '21 Jan 2023 15:00', parentKnobs.date_end, slidesKnobTab),
    links: randomLinks(knobNumber(
      'Number of links',
      2,
      {
        range: true,
        min: 0,
        max: 10,
        step: 1,
      },
      parentKnobs.number_of_links,
      slidesKnobTab,
    ), 10),
    ...slotKnobs([
      'content_top',
      'content_bottom',
    ]),
  }).join(' ');

  const knobs = {
    theme,
    title: knobText('Title', 'Slider title', parentKnobs.title, parentKnobs.knobTab),
    with_background: knobBoolean('With background', false, parentKnobs.with_background, parentKnobs.knobTab),
    vertical_spacing: knobRadios(
      'Vertical spacing',
      {
        None: 'none',
        Top: 'top',
        Bottom: 'bottom',
        Both: 'both',
      },
      'none',
      parentKnobs.vertical_spacing,
      parentKnobs.knobTab,
    ),
    slides,
    previous_label: knobText('Previous Label', 'Previous', parentKnobs.previous_label, parentKnobs.knobTab),
    next_label: knobText('Next Label', 'Next', parentKnobs.next_label, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
  };

  return shouldRender(parentKnobs) ? CivicThemeSlider({
    ...knobs,
    ...slotKnobs([
      'content_top',
      'content_bottom',
    ]),
  }) : knobs;
};
