// phpcs:ignoreFile
import ContentLink from '../../01-atoms/content-link/content-link.twig';
import Button from '../../01-atoms/button/button.twig';
import Table from '../../01-atoms/table/table.twig';
import Figure from '../figure/figure.twig';
import VideoPlayer from '../video-player/video-player.twig';

export default {
  args: (theme = 'light') => ({
    theme,
    content: `<h1>Heading 1</h1>
      <h2>Heading 2</h2>
      <h3>Heading 3</h3>
      <h4>Heading 4</h4>
      <h5>Heading 5</h5>
      <h6>Heading 6</h6>
      <p>Text without a class sed aute in sed consequat veniam excepteur minim mollit.</p>
      <p class="ct-text-large">Large text sed aute in sed consequat veniam excepteur minim mollit.</p>
      <p class="ct-text-regular">Regular text veniam reprehenderit velit ea veniam occaecat magna est sed duis quis elit occaecat dolore ut enim est do in dolor non elit aliquip commodo aliquip sint veniam ullamco adipisicing tempor ad.</p>
      <p class="ct-text-small">Small text <span>duis sunt velit.</span><span>Ea eu non.</span></p>
      <p>In mollit in minim ut non ${ContentLink({
      theme,
      text: 'commodo dolore',
      url: 'https://example.com',
    })} nisi anim.</p>
      <p>Deserunt in ex dolore. <sup>Super cupidatat esse.</sup> <sub>Sub do mollit aute labore.</sub></p>
      <p>Primary button link within text mollit in minim ut non ${Button({
      theme,
      kind: 'link',
      type: 'primary',
      text: 'Primary button text',
      url: 'https://example.com',
    })} nisi anim.</p>
      <p>Secondary button link within text mollit in minim ut non ${Button({
      theme,
      kind: 'link',
      type: 'secondary',
      text: 'Secondary button text',
      url: 'https://example.com',
    })} nisi anim.</p>
      <p>Tertiary button link within text mollit in minim ut non ${Button({
      theme,
      kind: 'link',
      type: 'tertiary',
      text: 'Tertiary button text',
      url: 'https://example.com',
    })} nisi anim.</p>
      <p>Sed aute in sed consequat veniam excepteur minim mollit.</p>
      <blockquote>Culpa laboris sit fugiat minim ad commodo eu id sint eu sed nisi.</blockquote>
      <blockquote>Culpa laboris sit fugiat minim ad commodo eu id sint eu sed nisi.<cite>Sed aute</cite></blockquote>
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
      ${
    Figure({
      theme,
      url: './demo/images/demo1.jpg',
      alt: 'Occaecat laborum voluptate cupidatat.',
      caption: 'Commodo anim sint minim.',
    })
    }
      ${
    VideoPlayer({
      theme,
      sources: [
        {
          url: 'demo/videos/demo.webm',
          type: 'video/webm',
        },
        {
          url: 'demo/videos/demo.mp4',
          type: 'video/mp4',
        },
        {
          url: 'demo/videos/demo.avi',
          type: 'video/avi',
        },
      ],
      poster: 'demo/videos/demo_poster.png',
      transcript_link: {
        text: 'View transcript',
        title: 'Open transcription in a new window',
        url: 'https://example.com',
        is_new_window: true,
        is_external: false,
        attributes: null,
      },
    })
    }
      ${
    Table({
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
    })
    }
      `,
    is_contained: true,
    vertical_spacing: 'none',
    with_background: false,
    modifier_class: '',
    attributes: null,
  }),
};
