import {
  randomFormElements, randomInt, randomString,
} from '../../00-base/base.utils';

import CivicThemeGroupFilter from './group-filter.twig';

const meta = {
  title: 'Molecules/Group Filter',
  component: CivicThemeGroupFilter,
  parameters: {
    layout: 'fullscreen',
  },
  render: (args) => {
    const filters = [];
    if (args.filter_number > 0) {
      for (let i = 0; i < args.filter_number; i++) {
        filters.push({
          content: randomFormElements(1, args.theme, true)[0],
          title: `Filter ${randomString(randomInt(3, 8))} ${i + 1}`,
        });
      }
    }
    return CivicThemeGroupFilter({
      ...args,
      filters,
    });
  },
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    filter_number: {
      control: { type: 'range', min: 0, max: 10, step: 1 },
    },
    title: {
      control: { type: 'text' },
    },
    submit_text: {
      control: { type: 'text' },
    },
    attributes: {
      control: { type: 'text' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const GroupFilter = {
  args: {
    theme: 'light',
    filter_number: 3,
    title: 'Filter search results by:',
    submit_text: 'Apply',
    attributes: '',
    modifier_class: '',
  },
};
