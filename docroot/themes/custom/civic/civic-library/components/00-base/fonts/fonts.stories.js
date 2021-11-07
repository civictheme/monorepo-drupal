export default {
  title: 'Base/Fonts',
  parameters: {
    layout: 'centered',
  },
};

export const Fonts = () => {
  const Fonts = [...new Set([
    ...SCSS_VARIABLES['civic-fonts-default'],
    ...SCSS_VARIABLES['civic-fonts'],
  ])];

  let html = '';

  for (const i in Object.values(Fonts)) {
    html += `<div class="example-container">`;
    html += `<div class="example-container__title">${Fonts[i]}</div>`;
    html += `<div class="example-container__content story-font--${Fonts[i]}">The quick brown fox jumps over the lazy dog</div>`;
    html += `</div>`;
  }

  return `<div class="story-fonts-wrapper story-wrapper-size--large">${html}</div>`;
};
