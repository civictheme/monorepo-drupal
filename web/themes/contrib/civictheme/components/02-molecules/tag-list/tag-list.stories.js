import Component from './tag-list.twig';

const meta = {
  title: 'Molecules/Tag List',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    tags: {
      control: { type: 'array' },
    },
    vertical_spacing: {
      control: { type: 'radio' },
      options: ['none', 'top', 'bottom', 'both'],
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
    attributes: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const TagList = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    tags: [
      'Tag 1',
      'Tag 2',
    ],
    vertical_spacing: 'none',
    content_top: '',
    content_bottom: '',
    modifier_class: '',
    attributes: '',
  },
};
