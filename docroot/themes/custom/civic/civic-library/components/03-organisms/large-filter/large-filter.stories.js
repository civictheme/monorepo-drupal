import { radios, number, text, boolean } from '@storybook/addon-knobs';

import { dropDownFilter, formElement } from '../../00-base/base.stories';

import DropdownFilter from '../../02-molecules/dropdown-filter/dropdown-filter.twig';
import CivicLargeFilter from './large-filter.twig';

export default {
  title: 'Organisms/Form',
  parameters: {
    layout: 'fullscreen',
  },
};

export const LargeFilter = () => {
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
    filter_title: text('Filter title', 'Filter search results by:', generalKnobTab),
    tags_title: text('Tag title', 'Selected filters:', generalKnobTab),
    clear_text: text('Clear all button text', 'Clear all', generalKnobTab),
  };

  let count = 0;
  const filters = [];
  const filterGroup = 'filter_group';

  if (boolean('Show example filters', true, generalKnobTab)) {

    // Example checkbox dropdown.
    filters.push(DropdownFilter({
      theme,
      filter_text: 'Checkboxes',
      filter_group: filterGroup,
      options_title: 'Type of items',
      type: 'checkbox',
      options: [
        formElement('checkbox', { value: true }, theme, false, count++),
        formElement('checkbox', { value: true }, theme, false, count++),
        formElement('checkbox', { value: true }, theme, false, count++),
        formElement('checkbox', { value: false }, theme, false, count++),
        formElement('checkbox', { value: false }, theme, false, count++),
      ].join(''),
    }));

    // Example date dropdown.
    filters.push(DropdownFilter({
      theme,
      filter_text: 'Dates',
      filter_group: filterGroup,
      type: 'date',
      options: [
        formElement('date', {
          required: false,
          description: false,
          value: '',
        }, theme, false, count++),
        formElement('date', {
          required: false,
          description: false,
          value: '',
        }, theme, false, count++),
       ].join(''),
    }));
  }

  // Random dropdowns.
  const filterNumber = number(
    'Number of extra filters',
    0,
    {
      range: true,
      min: 0,
      max: 5,
      step: 1,
    },
    generalKnobTab,
  );

  if (filterNumber > 0) {
    for (let i = 0; i < filterNumber; i++) {
      const inputType = ['radio', 'checkbox', 'date'][Math.round(Math.random() * 2)];
      filters.push(dropDownFilter(inputType, 4, theme, true, count++));
    }
  }

  // Sort by example.
  if (boolean('Show sort by', true, generalKnobTab)) {
    filters.push(DropdownFilter({
      theme,
      filter_text: 'Sort by',
      filter_group: filterGroup,
      type: 'select',
      modifier_class: 'civic-dropdown-filter--right',
      options: [
        formElement('select', {
          value: [
            { type: 'option', value: 'relevant', label: 'Relevant' },
            { type: 'option', value: 'a-z', label: 'A-Z' },
            { type: 'option', value: 'z-a', label: 'Z-A' },
          ],
          attributes: 'data-large-filter-ignore'
        }, theme, false, count++),
      ],
    }));
  }

  return CivicLargeFilter({
    ...generalKnobs,
    filters: filters.join(''),
  });
};
