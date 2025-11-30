// phpcs:ignoreFile
import { placeholder, randomSentence } from './storybook.utils';

export default {
  title: 'Base/Storybook/Layout',
  parameters: {
    layout: 'fullscreen',
  },
};

const createStory = ({ storyName, layout, storyLayoutSize, storyLayoutCenteredVertically, storyLayoutIsContainer, storyLayoutIsResizable, storyLayoutClass, storyLayoutHtmlBefore, storyLayoutHtmlAfter }) => ({
  storyName,
  parameters: {
    layout,
    storyLayoutSize,
    storyLayoutCenteredVertically,
    storyLayoutIsContainer,
    storyLayoutIsResizable,
    storyLayoutClass,
    storyLayoutHtmlBefore,
    storyLayoutHtmlAfter,
  },
  render: () => placeholder(randomSentence(50, `${storyLayoutSize}-${layout}`)),
});

// Initial six permutations (size + layout) without decoration
export const Story1 = createStory({
  storyName: 'Small Fullscreen',
  layout: 'fullscreen',
  storyLayoutSize: 'small',
  storyLayoutCenteredVertically: false,
  storyLayoutIsContainer: false,
  storyLayoutIsResizable: false,
  storyLayoutClass: '',
  storyLayoutHtmlBefore: '',
  storyLayoutHtmlAfter: '',
});

export const Story2 = createStory({
  storyName: 'Medium Fullscreen',
  layout: 'fullscreen',
  storyLayoutSize: 'medium',
  storyLayoutCenteredVertically: false,
  storyLayoutIsContainer: false,
  storyLayoutIsResizable: false,
  storyLayoutClass: '',
  storyLayoutHtmlBefore: '',
  storyLayoutHtmlAfter: '',
});

export const Story3 = createStory({
  storyName: 'Large Fullscreen',
  layout: 'fullscreen',
  storyLayoutSize: 'large',
  storyLayoutCenteredVertically: false,
  storyLayoutIsContainer: false,
  storyLayoutIsResizable: false,
  storyLayoutClass: '',
  storyLayoutHtmlBefore: '',
  storyLayoutHtmlAfter: '',
});

export const Story4 = createStory({
  storyName: 'Small Centered',
  layout: 'centered',
  storyLayoutSize: 'small',
  storyLayoutCenteredVertically: false,
  storyLayoutIsContainer: false,
  storyLayoutIsResizable: false,
  storyLayoutClass: '',
  storyLayoutHtmlBefore: '',
  storyLayoutHtmlAfter: '',
});

export const Story5 = createStory({
  storyName: 'Medium Centered',
  layout: 'centered',
  storyLayoutSize: 'medium',
  storyLayoutCenteredVertically: false,
  storyLayoutIsContainer: false,
  storyLayoutIsResizable: false,
  storyLayoutClass: '',
  storyLayoutHtmlBefore: '',
  storyLayoutHtmlAfter: '',
});

export const Story6 = createStory({
  storyName: 'Large Centered',
  layout: 'centered',
  storyLayoutSize: 'large',
  storyLayoutCenteredVertically: false,
  storyLayoutIsContainer: false,
  storyLayoutIsResizable: false,
  storyLayoutClass: '',
  storyLayoutHtmlBefore: '',
  storyLayoutHtmlAfter: '',
});

export const Story7 = createStory({
  storyName: 'Medium Fullscreen Centered',
  layout: 'fullscreen',
  storyLayoutSize: 'medium',
  storyLayoutCenteredVertically: true,
  storyLayoutIsContainer: true,
  storyLayoutIsResizable: false,
  storyLayoutClass: 'someclass',
  storyLayoutHtmlBefore: '<em>HTML before</em>',
  storyLayoutHtmlAfter: '<em>HTML after</em>',
});

export const Story8 = createStory({
  storyName: 'Medium Centered Centered',
  layout: 'centered',
  storyLayoutSize: 'medium',
  storyLayoutCenteredVertically: true,
  storyLayoutIsContainer: true,
  storyLayoutIsResizable: false,
  storyLayoutClass: 'someclass',
  storyLayoutHtmlBefore: '<em>HTML before</em>',
  storyLayoutHtmlAfter: '<em>HTML after</em>',
});

export const Story9 = createStory({
  storyName: 'Medium Fullscreen Centered Resizable',
  layout: 'fullscreen',
  storyLayoutSize: 'medium',
  storyLayoutCenteredVertically: true,
  storyLayoutIsContainer: true,
  storyLayoutIsResizable: true,
  storyLayoutClass: 'someclass',
  storyLayoutHtmlBefore: '<em>HTML before</em>',
  storyLayoutHtmlAfter: '<em>HTML after</em>',
});

export const Story10 = createStory({
  storyName: 'Medium Centered Centered Resizable',
  layout: 'centered',
  storyLayoutSize: 'medium',
  storyLayoutCenteredVertically: true,
  storyLayoutIsContainer: true,
  storyLayoutIsResizable: true,
  storyLayoutClass: 'someclass',
  storyLayoutHtmlBefore: '<em>HTML before</em>',
  storyLayoutHtmlAfter: '<em>HTML after</em>',
});
