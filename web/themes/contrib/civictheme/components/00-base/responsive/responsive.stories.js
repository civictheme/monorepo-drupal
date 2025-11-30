// phpcs:ignoreFile
import ResponsiveStoryTemplate from './responsive.stories.twig';
import './responsive';
import '../collapsible/collapsible';

export default {
  title: 'Base/Utilities/Responsive',
  parameters: {
    layout: 'centered',
    storyLayoutSize: 'medium',
    docs: 'Try resizing your browser window to see how components react to a breakpoint change',
  },
};

export const Responsive = () => ResponsiveStoryTemplate();
