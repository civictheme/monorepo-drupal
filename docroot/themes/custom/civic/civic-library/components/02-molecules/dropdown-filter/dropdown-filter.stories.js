import {
  boolean,
  radios,
  text,
  number,
} from '@storybook/addon-knobs';

import { formElement } from '../../00-base/base.stories';

import DropdownFilter from './dropdown-filter.twig';

export default {
  title: 'Molecules/Filter',
  parameters: {
    layout: 'centered',
  },
};

export const DropDownFilter = () => {
  const generalKnobTab = 'General';
  const theme = radios(
    'Theme',
    {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
    generalKnobTab,
  );

  const generalKnobs = {
    theme,
    filter_text: text('Filter text', 'Filter text', generalKnobTab),
    filter_group: text('Filter group name', 'civic_filter_group', generalKnobTab),
    options_title: text('Options title', 'Options title', generalKnobTab),
  };

  const inputType = radios(
    'Filter type',
    {
      Checkboxes: 'checkbox',
      Radios: 'radio',
    },
    'radio',
    generalKnobTab,
  );

  generalKnobs.type = inputType;

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

  const withOptionTitle = boolean('With options title', true, generalKnobTab);

  generalKnobs.options_title = withOptionTitle ? text('Options title', 'Options title (optional)', generalKnobTab) : '';

  const children = [];
  for (let i = 1; i <= optionNumber; i++) {
    const options = {
      required: false,
      description: false,
      attributes: '',
    };
    if (inputType === 'radio') {
      options.attributes += 'name="test"';
    }
    children.push(formElement(inputType, options, theme, false, i));
  }

  const html = DropdownFilter({
    ...generalKnobs,
    options: children.join(''),
  });

  return `<div class="container"><div class="row"><div class="col-xs-12">${html}</div></div></div>`;
};
