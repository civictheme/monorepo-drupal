import { boolean, radios, text } from '@storybook/addon-knobs';

import CivicVideo from './video.twig';

export default {
  title: 'Atom/Video',
};

export const Video = () => CivicVideo({
  theme: radios(
    'Theme',
    {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
  ),
  src: text('URL', 'https://www.youtube.com/embed/C0DPdy98e4c'),
  title: text('Title', 'Test video'),
  width: text('Width', 854),
  height: text('Height', 450),
  transcript_link: {
    text: text('Transcript link text', 'View transcript'),
    title: text('Transcript link title', 'Opens in new tab'),
    url: text('Transcript link url', 'https://example.com'),
    new_window: boolean('Transcript link opens in new window', true),
    is_external: boolean('Share link - is external', true),
  },
  share_link: {
    text: text('Share link text', 'Share this video'),
    title: text('Share link title', 'Opens in new tab'),
    url: text('Share link url', 'https://example.com'),
    new_window: boolean('Share link opens in new window', true),
    is_external: boolean('Share link - is external', true),
  },
});
