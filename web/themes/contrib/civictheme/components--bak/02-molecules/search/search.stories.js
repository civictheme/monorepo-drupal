// phpcs:ignoreFile
/**
 * CivicTheme Search component stories.
 */

import Component from './search.twig';

const meta = {
  title: 'Molecules/Search',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    text: {
      control: { type: 'text' },
    },
    url: {
      control: { type: 'text' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Search = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    text: 'Search',
    url: '/search',
    modifier_class: '',
    attributes: null,
  },
};
