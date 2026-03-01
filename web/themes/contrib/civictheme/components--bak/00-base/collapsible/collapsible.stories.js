// phpcs:ignoreFile
import Component from './collapsible.stories.twig';

const meta = {
  title: 'Base/Utilities/Collapsible',
  component: Component,
};

export default meta;

export const Collapsible = {
  parameters: {
    layout: 'centered',
    html: {
      disable: true,
    },
  },
};
