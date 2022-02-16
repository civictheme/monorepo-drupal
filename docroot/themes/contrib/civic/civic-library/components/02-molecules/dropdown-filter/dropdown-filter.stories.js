import {
  boolean,
  radios,
  text,
  number,
} from '@storybook/addon-knobs';

import { formElement } from '../../00-base/base.stories';

import DropdownFilter from './dropdown-filter.twig';

import './dropdown-filter';

export default {
  title: 'Molecules/Filter',
  parameters: {
    layout: 'centered',
  },
};

export const DropDownFilter = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const theme = radios(
    'Theme',
    {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
    generalKnobTab,
  );

  const type = radios(
    'Filter type',
    {
      Checkboxes: 'checkbox',
      Radios: 'radio',
    },
    'radio',
    generalKnobTab,
  );

  const optionNumber = number(
    'Number of options',
    4,
    {
      range: true,
      min: 0,
      max: 7,
      step: 1,
    },
    generalKnobTab,
  );

  const children = [];
  for (let i = 1; i <= optionNumber; i++) {
    const options = {
      required: false,
      description: false,
      attributes: (type === 'radio') ? 'name="test"' : '',
    };
    children.push(formElement(type, options, theme, false, i));
  }

  const generalKnobs = {
    theme,
    filter_text: text('Filter text', 'Filter text', generalKnobTab),
    filter_group: text('Filter group name', 'civic_filter_group', generalKnobTab),
    type,
    options_title: boolean('With options title', true, generalKnobTab) ? text('Options title', 'Options title (optional)', generalKnobTab) : '',
    options: children.join(''),
  };

  return DropdownFilter({
    ...generalKnobs,
  });
};
