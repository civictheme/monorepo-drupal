// phpcs:ignoreFile
/**
 * CivicTheme Text Icon component stories.
 */

import Component from './text-icon.twig';
import Constants from '../../../dist/constants.json'; // eslint-disable-line import/no-unresolved

const meta = {
  title: 'Base/Text Icon',
  component: Component,
  argTypes: {
    text: {
      control: { type: 'text' },
    },
    is_new_window: {
      control: { type: 'boolean' },
    },
    is_external: {
      control: { type: 'boolean' },
    },
    icon: {
      control: { type: 'select' },
      options: Constants.ICONS,
    },
    icon_placement: {
      control: { type: 'radio' },
      options: ['before', 'after'],
    },
    icon_class: {
      control: { type: 'text' },
    },
    icon_group_disabled: {
      control: { type: 'boolean' },
    },
    icon_single_only: {
      control: { type: 'boolean' },
    },
  },
};

export default meta;

export const TextIcon = {
  parameters: {
    layout: 'centered',
  },
  args: {
    text: 'Text icon example',
    is_new_window: false,
    is_external: false,
    icon: Constants.ICONS[0],
    icon_placement: 'before',
    icon_class: '',
    icon_group_disabled: false,
    icon_single_only: false,
  },
};
