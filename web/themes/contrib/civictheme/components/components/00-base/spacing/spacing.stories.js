// phpcs:ignoreFile
import { placeholder } from '../base.utils';

export default {
  title: 'Base/Spacing',
};

export const Spacing = () => {
  let html = '';

  let spacingHtml = '';
  for (let i = 1; i <= 10; i++) {
    spacingHtml = `<div class="story-spacing--${i}">${spacingHtml}</div>`;
  }

  html += `<div class="example-container story-wrapper-size--large story-wrapper--centered">`;
  html += `<div class="example-container__title">Spacing</div>`;
  html += `<div class="example-container__content">`;
  html += `<div class="story-spacing-wrapper ">${spacingHtml}</div>`;
  html += `</div>`;
  html += `</div>`;

  const componentSpacings = [
    'top',
    'bottom',
    'both',
    'none',
  ];

  let spacingComponentHtml = '';
  componentSpacings.forEach((type) => {
    const className = `ct-vertical-spacing--${type}`;
    spacingComponentHtml += `<div class="story-vertical-spacing-container"><div class="story-vertical-spacing ${className}">${placeholder(`Content <code>.${className}</code>`)}</div></div><hr>`;
  });

  html += `<div class="example-container story-wrapper-size--large story-wrapper--centered">`;
  html += `<div class="example-container__title">Component Vertical Spacing</div>`;
  html += `<div class="example-container__content">`;
  html += `<div class="story-vertical-spacing-wrapper ">${spacingComponentHtml}</div>`;
  html += `</div>`;
  html += `</div>`;

  let spacingInsetComponentHtml = '';
  componentSpacings.forEach((type) => {
    const className = `ct-vertical-spacing-inset--${type}`;
    spacingInsetComponentHtml += `<div class="story-vertical-spacing-container"><div class="story-vertical-spacing ${className}">${placeholder(`Content <code>.${className}</code>`)}</div></div><hr>`;
  });

  html += `<div class="example-container story-wrapper-size--large story-wrapper--centered">`;
  html += `<div class="example-container__title">Component Vertical Spacing Inset</div>`;
  html += `<div class="example-container__content">`;
  html += `<div class="story-vertical-spacing-wrapper ">${spacingInsetComponentHtml}</div>`;
  html += `</div>`;
  html += `</div>`;

  return html;
};
