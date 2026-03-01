// phpcs:ignoreFile
import Component from './flyout.stories.twig';

const meta = {
  title: 'Base/Utilities/Flyout',
  component: Component,
  argTypes: {
    direction: {
      control: { type: 'select' },
      options: ['top', 'bottom', 'left', 'right'],
    },
    expanded: {
      control: { type: 'boolean' },
    },
    duration: {
      control: { type: 'number' },
    },
  },
};

export default meta;

export const Flyout = {
  parameters: {
    layout: 'centered',
    html: {
      disable: true,
    },
  },
  args: {
    direction: 'right',
    expanded: false,
    duration: 500,
  },
};
