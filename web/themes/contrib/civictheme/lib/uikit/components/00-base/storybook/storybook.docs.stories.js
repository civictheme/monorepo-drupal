// phpcs:ignoreFile
import { placeholder, randomSentence } from './storybook.utils';

export default {
  title: 'Base/Storybook/Docs',
  parameters: {
    layout: 'fullscreen',
  },
};

const createStory = ({ storyName, docs, docsSize, docsClass, docsPlacement }) => ({
  storyName,
  parameters: {
    docs,
    docsSize,
    docsClass,
    docsPlacement,
  },
  render: () => placeholder('Story content'),
});

export const DocStory1 = createStory({
  storyName: 'Small Docs Before',
  docs: placeholder(`This is a small doc before. ${randomSentence(50, 'small-docs-before')}`),
  docsSize: 'small',
  docsClass: '',
  docsPlacement: 'before',
});

export const DocStory2 = createStory({
  storyName: 'Small Docs After',
  docs: placeholder(`This is a small doc after. ${randomSentence(50, 'small-docs-after')}`),
  docsSize: 'small',
  docsClass: '',
  docsPlacement: 'after',
});

export const DocStory3 = createStory({
  storyName: 'Medium Docs Before',
  docs: placeholder(`This is a medium doc before. ${randomSentence(50, 'medium-docs-before')}`),
  docsSize: 'medium',
  docsClass: '',
  docsPlacement: 'before',
});

export const DocStory4 = createStory({
  storyName: 'Medium Docs After',
  docs: placeholder(`This is a medium doc after. ${randomSentence(50, 'medium-docs-after')}`),
  docsSize: 'medium',
  docsClass: '',
  docsPlacement: 'after',
});

export const DocStory5 = createStory({
  storyName: 'Large Docs Before',
  docs: placeholder(`This is a large doc before. ${randomSentence(50, 'large-docs-before')}`),
  docsSize: 'medium',
  docsClass: '',
  docsPlacement: 'before',
});

export const DocStory6 = createStory({
  storyName: 'Large Docs After',
  docs: placeholder(`This is a large doc after. ${randomSentence(50, 'large-docs-after')}`),
  docsSize: 'medium',
  docsClass: '',
  docsPlacement: 'after',
});

export const DocStory7 = createStory({
  storyName: 'Fluid Docs Before',
  docs: placeholder(`This is a fluid doc before. ${randomSentence(50, 'fluid-docs-before')}`),
  docsSize: 'medium',
  docsClass: '',
  docsPlacement: 'before',
});

export const DocStory8 = createStory({
  storyName: 'Fluid Docs After',
  docs: placeholder(`This is a fluid doc after with text aligned to right using the test class. ${randomSentence(50, 'fluid-docs-after')}`),
  docsSize: 'medium',
  docsClass: 'test-docs-align-right',
  docsPlacement: 'after',
});
