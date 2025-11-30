// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';

import CivicThemeBasicContent from './basic-content.twig';
import CivicThemeContentLink from '../../01-atoms/content-link/content-link.twig';
import CivicThemeButton from '../../01-atoms/button/button.twig';
import CivicThemeTable from '../../01-atoms/table/table.twig';
import CivicThemeFigure from '../figure/figure.twig';
import CivicThemeVideoPlayer from '../video-player/video-player.twig';
import {
  demoImage,
  demoVideoPoster,
  demoVideos,
} from '../../00-base/base.utils';

export default {
  title: 'Molecules/Basic Content',
  parameters: {
    layout: 'fullscreen',
    knobs: {
      escapeHTML: false,
    },
  },
};

export const BasicContent = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const theme = radios(
    'Theme',
    {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
    generalKnobTab,
  );

  let html = '';

  // Headings.
  html += `
    <h1>Heading 1</h1>
    <h2>Heading 2</h2>
    <h3>Heading 3</h3>
    <h4>Heading 4</h4>
    <h5>Heading 5</h5>
    <h6>Heading 6</h6>
  `;

  // Paragraphs.
  html += `
    <p>Text without a class sed aute in sed consequat veniam excepteur minim mollit.</p>
    <p class="ct-text-large">Large text sed aute in sed consequat veniam excepteur minim mollit.</p>
    <p class="ct-text-regular">Regular text veniam reprehenderit velit ea veniam occaecat magna est sed duis quis elit occaecat dolore ut enim est do in dolor non elit aliquip commodo aliquip sint veniam ullamco adipisicing tempor ad.</p>
    <p class="ct-text-small">Small text <span>duis sunt velit.</span><span>Ea eu non.</span></p>
    <p>In mollit in minim ut non ${CivicThemeContentLink({
    theme,
    text: 'commodo dolore',
    url: 'https://example.com',
  })} nisi anim.</p>
    <p>Deserunt in ex dolore. <sup>Super cupidatat esse.</sup> <sub>Sub do mollit aute labore.</sub></p>
    <p>Primary button link within text mollit in minim ut non ${CivicThemeButton({
    theme,
    kind: 'link',
    type: 'primary',
    text: 'Primary button text',
    url: 'https://example.com',
  })} nisi anim.</p>
    <p>Secondary button link within text mollit in minim ut non ${CivicThemeButton({
    theme,
    kind: 'link',
    type: 'secondary',
    text: 'Secondary button text',
    url: 'https://example.com',
  })} nisi anim.</p>
    <p>Tertiary button link within text mollit in minim ut non ${CivicThemeButton({
    theme,
    kind: 'link',
    type: 'tertiary',
    text: 'Tertiary button text',
    url: 'https://example.com',
  })} nisi anim.</p>
    <p>Sed aute in sed consequat veniam excepteur minim mollit.</p>
  `;

  // Blockquote.
  html += `
    <blockquote>Culpa laboris sit fugiat minim ad commodo eu id sint eu sed nisi.</blockquote>
    <blockquote>Culpa laboris sit fugiat minim ad commodo eu id sint eu sed nisi.<cite>Sed aute</cite></blockquote>
  `;

  // Lists.
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
    <p>Number list with bullet children</p>
    <ol>
        <li>Number</li>
        <li>Number</li>
        <li>Number
          <ul>
            <li>Bullet</li>
            <li>Bullet</li>
          </ul>
        </li>
        <li>Number</li>
        <li>Number</li>
    </ol>
    <p>Bullet list with number children</p>
    <ul>
        <li>Bullet</li>
        <li>Bullet
          <ol>
            <li>Number</li>
            <li>Number</li>
          </ol>
        </li>
        <li>Bullet</li>
        <li>Bullet</li>
        <li>Bullet</li>
    </ul>
  `;

  // Image.
  html += CivicThemeFigure({
    theme,
    url: demoImage(),
    alt: 'Occaecat laborum voluptate cupidatat.',
    caption: 'Commodo anim sint minim.',
  });

  // Video Player.
  html += CivicThemeVideoPlayer({
    theme,
    sources: demoVideos(),
    poster: demoVideoPoster(),
  });

  // Table.
  html += CivicThemeTable({
    theme,
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
    ],
  });

  const generalKnobs = {
    theme,
    content: boolean('Content', true, generalKnobTab) ? html : null,
    is_contained: boolean('Contained', true, generalKnobTab),
    with_background: boolean('With background', false, generalKnobTab),
    vertical_spacing: radios(
      'Vertical spacing',
      {
        None: 'none',
        Top: 'top',
        Bottom: 'bottom',
        Both: 'both',
      },
      'none',
      generalKnobTab,
    ),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeBasicContent({
    ...generalKnobs,
  });
};
