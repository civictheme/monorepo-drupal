// phpcs:ignoreFile
/**
 * CivicTheme Group Filter component stories.
 */

import Component from './group-filter.twig';
import GroupFilterData from './group-filter.stories.data';

const meta = {
  title: 'Molecules/List/Group Filter',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    title: {
      control: { type: 'text' },
    },
    filters: {
      control: { type: 'array' },
    },
    submit_text: {
      control: { type: 'text' },
    },
    form_hidden_fields: {
      control: { type: 'text' },
    },
    content_top: {
      control: { type: 'text' },
    },
    content_bottom: {
      control: { type: 'text' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
    group_id: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const GroupFilter = {
  parameters: {
    layout: 'padded',
  },
  args: GroupFilterData.args('light'),
};

export const GroupFilterDark = {
  parameters: {
    layout: 'padded',
  },

  args: GroupFilterData.args('dark'),

  globals: {
    backgrounds: {
      value: 'dark',
    },
  },
};

export const GroupFilterWithSelectedFilters = {
  parameters: {
    layout: 'padded',
  },
  args: GroupFilterData.args('light', { selectedFilters: true }),
};

export const GroupFilterWithSelectedFiltersDark = {
  parameters: {
    layout: 'padded',
    backgrounds: {
      default: 'Dark',
    },
  },
  args: GroupFilterData.args('dark', { selectedFilters: true }),
};
