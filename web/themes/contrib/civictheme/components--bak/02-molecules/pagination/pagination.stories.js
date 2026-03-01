// phpcs:ignoreFile
/**
 * CivicTheme Pagination component stories.
 */

import Component from './pagination.twig';
import PaginationData from './pagination.stories.data';

const meta = {
  title: 'Molecules/List/Pagination',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    heading_id: {
      control: { type: 'text' },
    },
    items: {
      control: { type: 'array' },
    },
    items_modifier_class: {
      control: { type: 'text' },
    },
    current: {
      control: { type: 'number' },
    },
    items_per_page_title: {
      control: { type: 'text' },
    },
    items_per_page_options: {
      control: { type: 'array' },
    },
    items_per_page_name: {
      control: { type: 'text' },
    },
    items_per_page_id: {
      control: { type: 'text' },
    },
    use_ellipsis: {
      control: { type: 'boolean' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Pagination = {
  parameters: {
    layout: 'padded',
  },
  args: PaginationData.args('light'),
};
