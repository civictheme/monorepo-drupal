import Component from './list.twig';
import ListData from './list.stories.data';

const meta = {
  title: 'Organisms/List',
  component: Component,
  argTypes: {
    title: {
      control: { type: 'text' },
    },
    link_above: {
      control: { type: 'object' },
    },
    filters: {
      control: { type: 'text' },
    },
    results_count: {
      control: { type: 'text' },
    },
    rows_above: {
      control: { type: 'text' },
    },
    rows: {
      control: { type: 'text' },
    },
    rows_below: {
      control: { type: 'text' },
    },
    empty: {
      control: { type: 'text' },
    },
    pagination: {
      control: { type: 'text' },
    },
    footer: {
      control: { type: 'text' },
    },
    link_below: {
      control: { type: 'object' },
    },
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    vertical_spacing: {
      control: { type: 'radio' },
      options: ['none', 'top', 'bottom', 'both'],
    },
    with_background: {
      control: { type: 'boolean' },
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

export const List = {
  parameters: {
    layout: 'padded',
  },
  args: ListData.args('light'),
};

export const ListDark = {
  parameters: {
    layout: 'padded',
    backgrounds: {
      default: 'Dark',
    },
  },
  args: ListData.args('dark'),
};

export const ListGroupFilters = {
  parameters: {
    layout: 'padded',
  },
  args: ListData.args('light', { group: true }),
};

export const ListNavigationCard = {
  parameters: {
    layout: 'padded',
  },
  args: ListData.args('light', { component: 'navigation' }),
};

export const ListSnippet = {
  parameters: {
    layout: 'padded',
  },
  args: ListData.args('light', { component: 'snippet', columnCount: 1 }),
};
