// phpcs:ignoreFile
/**
 * CivicTheme Single Filter component stories.
 */

import Component from './single-filter.twig';
import SingleFilterData from './single-filter.stories.data';

const meta = {
  title: 'Molecules/List/Single Filter',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    content_top: {
      control: { type: 'text' },
    },
    title: {
      control: { type: 'text' },
    },
    form_hidden_fields: {
      control: { type: 'text' },
    },
    items: {
      control: { type: 'array' },
    },
    submit_text: {
      control: { type: 'text' },
    },
    reset_text: {
      control: { type: 'text' },
    },
    content_bottom: {
      control: { type: 'text' },
    },
    is_multiple: {
      control: { type: 'boolean' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const SingleFilter = {
  parameters: {
    layout: 'padded',
  },
  args: SingleFilterData.args('light'),
};
