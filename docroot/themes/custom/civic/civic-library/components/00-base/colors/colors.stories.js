import './colors.stories.scss'

export default {
  title: 'Base/Colors',
  parameters: {
    layout: 'centered',
  },
}

export const Colors = () => {
  const colors = [...new Set([...SCSS_VARIABLES['civic-default-colors'], ...SCSS_VARIABLES['civic-colors']])];

  let html = '';

  for (var i in colors) {
    html += `<div class="story-color--${colors[i]}"></div>`;
  }

  return `<div class="story-colors-wrapper story-wrapper-size--large">${html}</div>`;
}
