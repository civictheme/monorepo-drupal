// phpcs:ignoreFile
import Component from './scrollspy.stories.twig';

const meta = {
  title: 'Base/Utilities/Scrollspy',
  component: Component,
};

export default meta;

export const Scrollspy = {
  parameters: {
    layout: 'fullscreen',
    docs: 'Scroll the viewport to see elements appear when it reaches a specific pixel threshold.',
    html: {
      disable: true,
    },
  },
};
