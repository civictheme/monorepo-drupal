// phpcs:ignoreFile
/**
 * CivicTheme Tag component stories.
 */

import Component from './tag.twig';
import Constants from '../../../dist/constants.json'; // eslint-disable-line import/no-unresolved

const meta = {
  title: 'Atoms/Tag',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    type: {
      control: { type: 'radio' },
      options: ['primary', 'secondary', 'tertiary'],
    },
    content: {
      control: { type: 'text' },
    },
    icon: {
      control: { type: 'select' },
      options: Constants.ICONS,
    },
    icon_placement: {
      control: { type: 'radio' },
      options: ['before', 'after'],
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Tag = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    type: 'primary',
    content: 'Tag content',
    icon: '',
    icon_placement: 'before',
    url: 'https://www.example.com',
    is_new_window: false,
    is_external: false,
    attributes: null,
    modifier_class: '',
  },
};
