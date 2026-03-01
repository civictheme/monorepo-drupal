// phpcs:ignoreFile
/**
 * CivicTheme Navigation component stories.
 */

import Component from './navigation.twig';
import NavigationData from './navigation.stories.data';

const meta = {
  title: 'Organisms/Navigation/Navigation',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    name: {
      control: { type: 'text' },
    },
    items: {
      control: { type: 'array' },
    },
    title: {
      control: { type: 'text' },
    },
    type: {
      control: { type: 'radio' },
      options: ['none', 'inline', 'dropdown', 'drawer'],
    },
    variant: {
      control: { type: 'radio' },
      options: ['none', 'primary', 'secondary'],
    },
    dropdown_columns: {
      control: { type: 'number' },
    },
    dropdown_columns_fill: {
      control: { type: 'boolean' },
    },
    is_animated: {
      control: { type: 'boolean' },
    },
    menu_id: {
      control: { type: 'text' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Navigation = {
  parameters: {
    layout: 'centered',
  },
  args: NavigationData.args('light'),
};
