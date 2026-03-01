// phpcs:ignoreFile
/**
 * CivicTheme Item List component stories.
 */

import Component from './item-list.twig';

const meta = {
  title: 'Base/Item List',
  component: Component,
  argTypes: {
    direction: {
      control: { type: 'radio' },
      options: ['horizontal', 'vertical'],
    },
    size: {
      control: { type: 'radio' },
      options: ['large', 'regular', 'small'],
    },
    no_gap: {
      control: { type: 'boolean' },
    },
    items: {
      control: { type: 'array' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const ItemList = {
  parameters: {
    layout: 'centered',
  },
  args: {
    direction: 'horizontal',
    size: 'regular',
    no_gap: false,
    items: [
      '<div class="story-placeholder" contenteditable="true">Content placeholder<div>',
      '<div class="story-placeholder" contenteditable="true">Content placeholder<div>',
      '<div class="story-placeholder" contenteditable="true">Content placeholder<div>',
    ],
    modifier_class: '',
    attributes: null,
  },
};
