// phpcs:ignoreFile
export default {
  title: 'Base/Elevation',
};

export const Elevation = () => {
  let html = ``;

  html += `<div class="story-elevation-wrapper">`;

  for (let i = 1; i <= 4; i++) {
    html += `<div class="story-container">`;
    html += `<div class="story-container__title">Elevation ${i}</div>`;
    html += `<div class="story-container__content story-elevation--${i}"></div>`;
    html += `</div>`;
  }

  html += `</div>`;

  return html;
};
