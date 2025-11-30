import Component from './layout.twig';

const meta = {
  title: 'Base/Layout',
  component: Component,
  argTypes: {
    content_top: {
      control: { type: 'text' },
    },
    sidebar_top_left: {
      control: { type: 'text' },
    },
    sidebar_top_right: {
      control: { type: 'text' },
    },
    content: {
      control: { type: 'text' },
    },
    sidebar_bottom_left: {
      control: { type: 'text' },
    },
    sidebar_bottom_right: {
      control: { type: 'text' },
    },
    content_bottom: {
      control: { type: 'text' },
    },
    sidebar_top_right_attributes: {
      control: { type: 'text' },
    },
    content_attributes: {
      control: { type: 'text' },
    },
    sidebar_bottom_left_attributes: {
      control: { type: 'text' },
    },
    sidebar_bottom_right_attributes: {
      control: { type: 'text' },
    },
    hide_sidebar_left: {
      control: { type: 'boolean' },
    },
    hide_sidebar_right: {
      control: { type: 'boolean' },
    },
    is_contained: {
      control: { type: 'boolean' },
    },
    vertical_spacing: {
      control: { type: 'radio' },
      options: ['none', 'top', 'bottom', 'both'],
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

export const Layout = {
  parameters: {
    layout: 'padded',
  },
  args: {
    content_top: '<div class="story-placeholder" contenteditable="true">content_top</div>',
    sidebar_top_left: '<div class="story-placeholder" contenteditable="true">sidebar_top_left</div>',
    sidebar_top_right: '<div class="story-placeholder" contenteditable="true">sidebar_top_right</div>',
    content: '<div class="story-placeholder" contenteditable="true">content</div>',
    sidebar_bottom_left: '<div class="story-placeholder" contenteditable="true">sidebar_bottom_left</div>',
    sidebar_bottom_right: '<div class="story-placeholder" contenteditable="true">sidebar_bottom_right</div>',
    content_bottom: '<div class="story-placeholder" contenteditable="true">content_bottom</div>',
    sidebar_top_left_attributes: '',
    sidebar_top_right_attributes: '',
    content_attributes: '',
    sidebar_bottom_left_attributes: '',
    sidebar_bottom_right_attributes: '',
    hide_sidebar_left: false,
    hide_sidebar_right: false,
    is_contained: false,
    vertical_spacing: 'none',
    attributes: '',
    modifier_class: '',
  },
};
