export default {
  title: 'Base/Colors',
  parameters: {
    layout: 'centered',
  },
};

export const Colors = () => {
  const colors = [...new Set([
    ...SCSS_VARIABLES['civic-colors-default'],
    ...SCSS_VARIABLES['civic-colors-default-shades'],
    ...SCSS_VARIABLES['civic-colors-default-neutrals'],
    ...SCSS_VARIABLES['civic-colors'],
  ])];

  let html = '';

  for (const i in Object.values(colors)) {
    html += `<div class="story-color--${colors[i]}"></div>`;
  }

  return `<div class="story-colors-wrapper story-wrapper-size--large">${html}</div>`;
};
