// phpcs:ignoreFile
import Component from './typography.stories.twig';

const meta = {
  title: 'Base/Typography',
  component: Component,
};

export default meta;

export const Typography = {
  parameters: {
    layout: 'centered',
    html: {
      disable: true,
    },
  },
};
