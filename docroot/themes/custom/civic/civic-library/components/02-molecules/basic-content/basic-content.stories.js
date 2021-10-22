import { radios, text } from '@storybook/addon-knobs';

import CivicBasicContent from './basic-content.twig';
import CivicLink from '../../01-atoms/link/link.twig';
import CivicTable from '../../01-atoms/table/table.twig';
import CivicImage from '../../01-atoms/image/image.twig';
import CivicVideo from '../../01-atoms/video/video.twig';
import imageFile from '../../../assets/image.png';

export default {
  title: 'Molecules/Basic Content',
  parameters: {
    layout: 'fullscreen',
    knobs: {
      escapeHTML: false,
    },
  },
};

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

  let html = ''

  // Headings
  html += `
    <h1>Heading 1</h1>
    <h2>Heading 2</h2>
    <h3>Heading 3</h3>
    <h4>Heading 4</h4>
    <h5>Heading 5</h5>
    <h6>Heading 6</h6>
  `;

  // Paragraphs
  html += `
    <p class="lead">Lead sed aute in sed consequat veniam excepteur minim mollit.</p>
    <p>Body veniam reprehenderit velit ea veniam occaecat magna est sed duis quis elit occaecat dolore ut enim est do in dolor non elit aliquip commodo aliquip sint veniam ullamco adipisicing tempor ad.</p>
    <p class="small">Small <span>duis sunt velit.</span><span>Ea eu non.</span></p>
    <p>In mollit in minim ut non ${ CivicLink({ theme: theme, text: 'commodo dolore', url: 'https://example.com' }) } nisi anim.</p>
    <p>Deserunt in ex dolore. <sup>Super cupidatat esse.</sup> <sub>Sub do mollit aute labore.</sub></p>
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

  // Image
  html += CivicImage({
    theme: theme,
    src: imageFile,
    alt: 'Occaecat laborum voluptate cupidatat.',
    caption: 'Commodo anim sint minim.',
  });

  // Video
  html += CivicVideo({
    theme: theme,
    src: 'https://www.youtube.com/embed/C0DPdy98e4c',
  });

  // Table
  html += CivicTable({
    theme: theme,
    header: [
      'Column A',
      'Column B',
      'Column C',
    ],
    rows: [
      [
        'Do duis minim cupidatat eu.',
        'Ullamco sunt dolore.',
        'Dolor in officia.',
      ],
      [
        'Do duis minim cupidatat eu.',
        'Ullamco sunt dolore.',
        'Dolor in officia.',
      ],
      [
        'Lorem ipsum magna sint.',
        'Consequat qui anim.',
        'Lorem ipsum aliqua veniam deserunt.',
      ],
    ]
  });

  const generalKnobs = {
    theme: theme,
    content: html // TODO - Add this back when dev finished.--> text('Content', html, generalKnobTab),
  };

  return CivicBasicContent(generalKnobs);
}
