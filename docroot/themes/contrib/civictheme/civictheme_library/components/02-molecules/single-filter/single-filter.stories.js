// phpcs:ignoreFile
import {
  boolean, number, radios, text,
} from '@storybook/addon-knobs';

import CivicThemeSingleFilter from './single-filter.twig';
import { getSlots, randomLinks } from '../../00-base/base.utils';

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

  generalKnobs.items = count > 0 ? randomLinks(count, 7, null, 'Filter') : null;

  return CivicThemeSingleFilter({
    ...generalKnobs,
    ...getSlots([
      'content_top',
      'content_bottom',
    ]),
  });
};
