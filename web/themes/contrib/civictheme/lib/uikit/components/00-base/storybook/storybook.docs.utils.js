// phpcs:ignoreFile
//
// Custom docs decorators.
//

export const decoratorDocs = (content, context) => {
  if (context.parameters.docs) {
    const size = ['small', 'medium', 'large', 'fluid'].includes(context.parameters.docsSize) ? context.parameters.docsSize : 'fluid';

    let classes = [
      'story-docs',
      `story-docs-size--${size}`,
    ].filter(Boolean).join(' ');

    if (context.parameters.docsClass) {
      classes += ` ${context.parameters.docsClass}`;
    }

    if (context.parameters.docsPlacement === 'after') {
      content = `${typeof content === 'function' ? content() : content}<div class="${classes}">${context.parameters.docs}</div>`;
    } else {
      content = `<div class="${classes}">${context.parameters.docs}</div>${typeof content === 'function' ? content() : content}`;
    }
  }

  return typeof content === 'function' ? content() : content;
};
