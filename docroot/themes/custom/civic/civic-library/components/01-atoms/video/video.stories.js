import { radios, text, object } from '@storybook/addon-knobs';

import CivicVideo from './video.twig';
import './video.scss';

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
  src: text('video URL', 'https://www.youtube.com/embed/fIfXWDMYdfo'),
  title: text('title', 'Songlines to soar for science and country'),
  width: text('Width', 854),
  height: text('Height', 450),
  transcript_link: object('Transcript URL', {
    text: 'View transcript',
    title: 'Opens in new tab',
    url: 'www.google.com',
    new_window: true,
    is_external: false,
  }),
  share_link: object('Share URL', {
    text: 'Share this video',
    title: 'Opens in new tab',
    url: 'www.google.com',
    new_window: true,
    is_external: false,
  }),
});
