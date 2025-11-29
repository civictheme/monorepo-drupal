// phpcs:ignoreFile
import CollapsibleStoryTemplate from './collapsible.stories.twig';
import './collapsible';

export default {
  title: 'Base/Utilities/Collapsible',
  parameters: {
    layout: 'centered',
    storyLayoutSize: 'medium',
  },
};

export const Collapsible = CollapsibleStoryTemplate;
