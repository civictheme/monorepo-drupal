import Component from './list.twig';
import ListData from './list.stories.data';

const meta = {
  title: 'Organisms/List',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    title: {
      control: { type: 'text' },
    },
    content: {
      control: { type: 'text' },
    },
    link_above: {
      control: { type: 'object' },
    },
    link_below: {
      control: { type: 'object' },
    },
    vertical_spacing: {
      control: { type: 'radio' },
      options: ['none', 'top', 'bottom', 'both'],
    },
    with_background: {
      control: { type: 'boolean' },
    },
    filters: {
      control: false,
    },
    pager: {
      control: false,
    },
    rows: {
      control: false,
    },
    empty: {
      control: { type: 'text' },
    },
    results_count: {
      control: { type: 'text' },
    },
    rows_above: {
      control: { type: 'text' },
    },
    rows_below: {
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
    layout: 'fullscreen',
  },
  args: ListData.args('light'),
};
