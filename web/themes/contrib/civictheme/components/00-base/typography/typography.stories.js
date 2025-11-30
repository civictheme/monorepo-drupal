// phpcs:ignoreFile
import TypographyStoryTemplate from './typography.stories.twig';

export default {
  title: 'Base/Typography',
  parameters: {
    layout: 'centered',
    docs: 'Demonstration of typography rules defined in the design system.<br/>For fully styled content, see <code>Basic&nbsp;content</code> component.',
    docsSize: 'medium',
  },
};

export const Typography = () => TypographyStoryTemplate();
