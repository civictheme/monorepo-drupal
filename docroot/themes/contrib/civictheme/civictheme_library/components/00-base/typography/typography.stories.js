// phpcs:ignoreFile
import { radios, text } from '@storybook/addon-knobs';

import CivicThemeHeading from '../../01-atoms/heading/heading.twig';
import CivicThemeContentLink from '../../01-atoms/content-link/content-link.twig';
import CivicThemeTypography from './typography.twig';
import {
  randomSentence,
  capitalizeFirstLetter,
  randomText,
  randomUrl,
} from '../base.stories';

export default {
  title: 'Base/Typography',
};

const ExampleContainer = (title, content, contentClass) => {
  let html = '';
  html += `<div class="example-container">`;
  html += `<div class="example-container__title">${title}</div>`;
  html += `<div class="example-container__content civictheme-${contentClass}">${content}</div>`;
  html += `</div>`;

  return html;
};

const ExampleLink = (theme, title, url, isExternal) => CivicThemeContentLink({
  theme: () => theme,
  text: title,
  url: () => url,
  is_external: isExternal,
});

export const Typography = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const theme = radios(
    'Theme', {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
    generalKnobTab,
  );

  const typographyNames = [...new Set([
    ...SCSS_VARIABLES['civictheme-typography-default'],
    ...SCSS_VARIABLES['civictheme-typography'],
  ])];

  let html = '';
  let htag = 1;

  for (const i in Object.values(typographyNames)) {
    let content = randomSentence(50);
    let title = capitalizeFirstLetter(typographyNames[i].replace('-', ' '));
    title += `<code>${typographyNames[i]}</code>`;

    if (typographyNames[i].split('-')[0] === 'heading') {
      content = CivicThemeHeading({
        theme: () => theme,
        content: 'The quick brown fox jumps over the lazy dog',
        level: htag++,
      });
    }

    if (typographyNames[i].split('-')[0] === 'label') {
      content = randomText(2);
    }

    html += ExampleContainer(title, content, typographyNames[i]);
  }

  let paragraph = `<p>${randomSentence(250)}</p>`;
  html += ExampleContainer('Paragraph 1', paragraph, 'content');

  paragraph = `
    <p>Pellentesque in ipsum id orci porta dapibus. ${ExampleLink(theme, 'Internal link', '/test1', false)} convallis at tellus Curabitur non nulla sit amet nisl tempus convallis quis ac lectus.
    Pellentesque in ipsum id orci porta dapibus ${ExampleLink(theme, 'Internal link 2', '/test12', false)}.
    </p>
    <p>Quisque velit nisi, pretium ut lacinia in, elementum id enim. ${ExampleLink(theme, 'External link', randomUrl(), true)} Donec rutrum congue leo eget malesuada.
    Cras ultricies ligula sed magna dictum porta. ${ExampleLink(theme, 'External link 2', randomUrl(), true)}
    Praesent sapien massa, convallis a pellentesque nec, egestas non nisi.</p>

    <p>Internal links:
      <ul>
        <li>${ExampleLink(theme, 'Internal link example 1', '/test1', false)}</li>
        <li>${ExampleLink(theme, 'Internal link example 2', '/test12', false)}</li>
      </ul>
    </p>
    <p>External links:
      <ul>
        <li>${ExampleLink(theme, 'External link example 1', randomUrl(), true)}</li>
        <li>${ExampleLink(theme, 'External link example 2', randomUrl(), true)}</li>
      </ul>
    </p>

    <p><strong>Bold: Nulla quis lorem ut libero malesuada feugiat.</strong></p>
    <p><em>Italic: Nulla quis lorem ut libero malesuada feugiat.</em></p>
    <p><span style="text-decoration: underline;">Underline: Nulla quis lorem ut libero malesuada feugiat.</span></p>
    <p><span style="text-decoration: line-through;">Strikethrouh: Nulla quis lorem ut libero malesuada feugiat.</span></p>
    <p>Date: 20<sup>th&nbsp;</sup>August 2022</p>
    <p>H<sub>2</sub>0</p></p>
  `;
  html += ExampleContainer('Paragraph 2', paragraph, 'content');

  paragraph = `
    <p>${randomSentence(50)}</p>
    <div class="example-container__title">Unordered list</div>
    <ul>
      <li>List item 1</li>
      <li>List item 2</li>
      <li>List item 3</li>
      <li>List item 4</li>
      <li>List item 5</li>
    </ul>
    <div class="example-container__title">Unordered list with sub unordered list</div>
    <ul>
      <li>List item 1</li>
      <li>List item 2
        <ul>
          <li>List item 2</li>
          <li>List item 3
            <ul>
              <li>List item 2</li>
              <li>List item 3</li>
              <li>List item 4</li>
            </ul>
          </li>
          <li>List item 4</li>
        </ul>
      </li>
      <li>List item 3</li>
      <li>List item 4</li>
      <li>List item 5</li>
    </ul>
    <div class="example-container__title">Unordered list with sub ordered list</div>
    <ul>
      <li>List item 1</li>
      <li>List item 2
        <ol>
          <li>Ordered List item 2</li>
          <li>Ordered List item 3
            <ol>
              <li>Ordered List item 2</li>
              <li>Ordered List item 3</li>
              <li>Ordered List item 4</li>
            </ol>
          </li>
          <li>List item 4</li>
        </ol>
      </li>
      <li>List item 3</li>
      <li>List item 4</li>
      <li>List item 5</li>
    </ul>
    <div class="example-container__title">Ordered list</div>
    <ol>
      <li>Ordered list item 1</li>
      <li>Ordered list item 2</li>
      <li>Ordered list item 3</li>
      <li>Ordered list item 4</li>
      <li>Ordered list item 5</li>
    </ol>
    <div class="example-container__title">Ordered list with sub ordered list</div>
    <ol>
      <li>
        Ordered list item 1
        <ol>
          <li>Ordered list item 2</li>
          <li>Ordered list item 3</li>
        </ol>
      </li>
      <li>Ordered list item 2</li>
      <li>
        Ordered list item 3
        <ol>
          <li>Ordered list item 3</li>
          <li>Ordered list item 4
            <ol>
              <li>Ordered list item 3</li>
              <li>Ordered list item 4</li>
              <li>Ordered list item 5</li>
            </ol>
          </li>
          <li>Ordered list item 5 - ${ExampleLink(theme, 'External link in list', randomUrl(), true)}</li>
        </ol>
      </li>
      <li>Ordered list item 4</li>
      <li>Ordered list item 5</li>
    </ol>
    <div class="example-container__title">Ordered list with sub unordered list</div>
    <ol>
      <li>Ordered list item 1
        <ul>
          <li>Unordered list item 1</li>
          <li>Unordered list item 2</li>
        </ul>
      </li>
      <li>Ordered list item 2</li>
      <li>Ordered list item 3
        <ul>
          <li>Unordered list item 1</li>
          <li>Unordered list item 2
            <ul>
              <li>Unordered list item 1</li>
              <li>Unordered list item 2</li>
              <li>Unordered list item 3</li>
            </ul>
          </li>
          <li>Unordered list item 3</li>
        </ul>
      </li>
      <li>Ordered list item 4</li>
      <li>Ordered list item 5</li>
    </ol>`;

  html += ExampleContainer('Paragraph 3', paragraph, 'content');

  const generalKnobs = {
    theme,
    content: html,
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  return CivicThemeTypography({
    ...generalKnobs,
  });
};
