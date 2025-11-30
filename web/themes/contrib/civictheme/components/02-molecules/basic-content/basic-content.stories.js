import Component from './basic-content.twig';
import BasicContentData from './basic-content.stories.data';

const meta = {
  title: 'Molecules/Basic Content',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    content: {
      control: { type: 'text' },
    },
    is_contained: {
      control: { type: 'boolean' },
    },
    vertical_spacing: {
      control: { type: 'radio' },
      options: ['none', 'top', 'bottom', 'both'],
    },
    with_background: {
      control: { type: 'boolean' },
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

export const BasicContent = {
  parameters: {
    layout: 'fullscreen',
  },
  args: BasicContentData.args('light'),
};

export const BasicContentDark = {
  parameters: {
    layout: 'fullscreen',
    backgrounds: {
      default: 'Dark',
    },
  },
  args: BasicContentData.args('dark'),
};
