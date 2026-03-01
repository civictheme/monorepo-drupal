// phpcs:ignoreFile
/**
 * CivicTheme Layout component stories.
 */

import Component from './layout.twig';
import LayoutData from './layout.stories.data';

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
  args: LayoutData.args(),
};

export const LayoutTopLeftSidebar = {
  parameters: {
    layout: 'padded',
  },
  args: {
    ...LayoutData.args(),
    sidebar_top_right: '',
    sidebar_bottom_left: '',
    sidebar_bottom_right: '',
  },
};

export const LayoutBottomLeftSidebar = {
  parameters: {
    layout: 'padded',
  },
  args: {
    ...LayoutData.args(),
    sidebar_top_left: '',
    sidebar_top_right: '',
    sidebar_bottom_right: '',
  },
};

export const LayoutTopRightSidebar = {
  parameters: {
    layout: 'padded',
  },
  args: {
    ...LayoutData.args(),
    sidebar_top_left: '',
    sidebar_bottom_left: '',
    sidebar_bottom_right: '',
  },
};

export const LayoutBottomRightSidebar = {
  parameters: {
    layout: 'padded',
  },
  args: {
    ...LayoutData.args(),
    sidebar_top_left: '',
    sidebar_top_right: '',
    sidebar_bottom_left: '',
  },
};

export const LayoutNoSidebars = {
  parameters: {
    layout: 'padded',
  },
  args: {
    ...LayoutData.args(),
    sidebar_top_left: '',
    sidebar_top_right: '',
    sidebar_bottom_left: '',
    sidebar_bottom_right: '',
  },
};
