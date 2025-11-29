// phpcs:ignoreFile
//
// Custom story decorators.
//

export const decoratorStoryLayout = (content, context) => {
  const shouldWrap = Object.keys(context.parameters)
    .some((key) => key.startsWith('storyLayout'));

  if (!shouldWrap) {
    return content();
  }

  const size = ['small', 'medium', 'large'].includes(context.parameters.storyLayoutSize) ? context.parameters.storyLayoutSize : 'medium';

  const isCentered = context.parameters.layout === 'centered';

  let classes = [
    'story-layout',
    `story-layout-size--${size}`,
    context.parameters.storyLayoutCenteredVertically || isCentered ? 'story-layout--centered-vertically' : '',
    context.parameters.storyLayoutIsContainer ? 'story-layout--container' : '',
    context.parameters.storyLayoutIsResizable && (context.globals.resizer || false) ? 'story-layout--resizable' : '',
  ].filter(Boolean).join(' ');

  if (context.parameters.storyLayoutClass) {
    classes += ` ${context.parameters.storyLayoutClass}`;
  }

  context.parameters.storyLayoutHtmlBefore = context.parameters.storyLayoutHtmlBefore || '';
  context.parameters.storyLayoutHtmlAfter = context.parameters.storyLayoutHtmlAfter || '';

  return `<div class="${classes}">${context.parameters.storyLayoutHtmlBefore}${content()}${context.parameters.storyLayoutHtmlAfter}</div>`;
};
