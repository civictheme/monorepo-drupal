// phpcs:ignoreFile
import {
  boolean, number, radios, text,
} from '@storybook/addon-knobs';

import CivicThemeSingleFilter from './single-filter.twig';
import {
  getSlots, randomName, randomString,
} from '../../00-base/base.utils';

export default {
  title: 'Molecules/Single Filter',
  parameters: {
    layout: 'fullscreen',
  },
};

export const SingleFilter = (knobTab) => {
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
    title: text('Title', 'Filter search results by:', generalKnobTab),
    submit_text: text('Submit button text', 'Apply', generalKnobTab),
    is_multiple: boolean('Multiple', false, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  const count = number(
    'Number of filters',
    3,
    {
      range: true,
      min: 0,
      max: 15,
      step: 1,
    },
    generalKnobTab,
  );

  const selected = number(
    'Selected',
    0,
    {
      range: true,
      min: 0,
      max: count,
      step: 1,
    },
    generalKnobTab,
  );

  const items = [];
  const name = randomName(5);
  for (let i = 0; i < count; i++) {
    items.push({
      text: `Filter ${i + 1}${randomString(3)}`,
      name: generalKnobs.is_multiple ? name + (i + 1) : name,
      is_selected: generalKnobs.is_multiple ? (i + 1) <= selected : (i + 1) === selected,
      attributes: `id="${name}_${randomName(3)}_${i + 1}"`,
    });
  }

  generalKnobs.items = items;

  return CivicThemeSingleFilter({
    ...generalKnobs,
    ...getSlots([
      'content_top',
      'content_bottom',
    ]),
  });
};
