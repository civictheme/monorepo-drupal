// phpcs:ignoreFile
import {
  radios, number, text,
} from '@storybook/addon-knobs';

import {
  getSlots, randomFormElements, randomInt, randomString,
} from '../../00-base/base.utils';

import CivicThemeGroupFilter from './group-filter.twig';

export default {
  title: 'Molecules/Group Filter',
  parameters: {
    layout: 'fullscreen',
  },
};

export const GroupFilter = () => {
  const generalKnobTab = 'General';

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
    filter_number: number(
      'Number of filters',
      3,
      {
        range: true,
        min: 0,
        max: 10,
        step: 1,
      },
      generalKnobTab,
    ),
    title: text('Filter title', 'Filter search results by:', generalKnobTab),
    submit_text: text('Submit button text', 'Apply', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  const filters = [];

  if (generalKnobs.filter_number > 0) {
    for (let i = 0; i < generalKnobs.filter_number; i++) {
      filters.push({
        content: randomFormElements(1, generalKnobs.theme, true)[0],
        title: `Filter ${randomString(randomInt(3, 8))} ${i + 1}`,
      });
    }
  }

  return CivicThemeGroupFilter({
    ...generalKnobs,
    filters,
    ...getSlots([
      'content_top',
      'content_bottom',
    ]),
  });
};
