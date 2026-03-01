// phpcs:ignoreFile
/**
 * CivicTheme DateTime component stories.
 */

import Component from './datetime.twig';

const meta = {
  title: 'Base/Utilities/Datetime',
  component: Component,
  parameters: {
    layout: 'centered',
  },
  argTypes: {
    start: {
      control: { type: 'text' },
    },
    end: {
      control: { type: 'text' },
    },
    start_iso: {
      control: { type: 'date' },
    },
    end_iso: {
      control: { type: 'date' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
  args: {
    start: '',
    end: '',
    start_iso: '',
    end_iso: '',
  },
};

export default meta;

export const Datetime = {
  args: {
    start: '20 Jan 2023 11:00',
    end: '21 Jan 2023 15:00',
    start_iso: '2023-01-20T00:00:00.000Z',
    end_iso: '2023-01-21T04:00:00.000Z',
  },
};
