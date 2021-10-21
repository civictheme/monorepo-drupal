import { radios, text } from '@storybook/addon-knobs';

import CivicBasicContent from './basic-content.twig';

export default {
  title: 'Molecules/Basic Content',
  parameters: {
    layout: 'fullscreen',
    knobs: {
      escapeHTML: false,
    },
  },
};

let html = ''

// Headings
html += `
  <h1>Heading 1</h2>
  <h2>Heading 2</h2>
  <h3>Heading 3</h2>
  <h4>Heading 4</h2>
  <h5>Heading 5</h2>
  <h6>Heading 6</h2>
`;

// Paragraphs
html += `
  <p class="lead">Sed aute in sed consequat veniam excepteur minim mollit.</p>
  <p>Veniam reprehenderit velit ea veniam occaecat magna est sed duis quis elit occaecat dolore ut enim est do in dolor non elit aliquip commodo aliquip sint veniam ullamco adipisicing tempor ad.</p>
  <p><span>Duis sunt velit.</span><span>Ea eu non.</span></p>
  <p>In mollit in minim ut non <a class="civic-link" href="https://example.com">commodo dolore</a> nisi anim.</p>
`;

// Blockquote
html += `
  <blockquote cite="https://example.com">Culpa laboris sit fugiat minim ad commodo eu id sint eu sed nisi.</blockquote>
`;

// Lists
html += `
  <ul>
    <li>Sint pariatur quis tempor.</li>
    <li>Lorem ipsum dolore laborum nulla ut.</li>
    <li>Deserunt ullamco occaecat anim cillum.</li>
  </ul>
  <ol>
    <li>Id nostrud id sit nulla.</li>
    <li>Dolore ea cillum culpa nulla.</li>
    <li>Lorem ipsum ex excepteur.</li>
  </ol>
`;

// Table
html += `
  <div class="civic-table civic-theme-light civic-table--striped">
    <table>
      <thead>
        <tr>
          <th>Column A</th>
          <th>Column B</th>
          <th>Column C</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Do duis minim cupidatat eu.</td>
          <td>Ullamco sunt dolore.</td>
          <td>Dolor in officia.</td>
        </tr>
        <tr>
          <td>Lorem ipsum magna sint.</td>
          <td>Consequat qui anim.</td>
          <td>Lorem ipsum aliqua veniam deserunt.</td>
        </tr>
      </tbody>
    </table>
  </div>
`;

export const BasicContent = () => {
  const generalKnobTab = 'General';
  const theme = radios(
    'Theme', {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
    generalKnobTab,
  );

  const generalKnobs = {
    theme: theme,
    content: html // TODO - Add this back when dev finished.--> text('Content', html, generalKnobTab),
  };

  return CivicBasicContent(generalKnobs);
}
