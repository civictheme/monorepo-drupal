import { boolean, radios, text } from '@storybook/addon-knobs';

import CivicVideo from './video.twig';

export default {
  title: 'Molecules/Video',
};

export const Video = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const generalKnobs = {
    theme: radios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      generalKnobTab,
    ),
    src: text('URL', 'https://www.youtube.com/embed/C0DPdy98e4c', generalKnobTab),
    title: text('Title', 'Test video', generalKnobTab),
    width: text('Width', 854, generalKnobTab),
    height: text('Height', 450, generalKnobTab),
    transcript_link: {
      text: text('Transcript link text', 'View transcript', generalKnobTab),
      title: text('Transcript link title', 'Opens in new tab', generalKnobTab),
      url: text('Transcript link url', 'https://example.com', generalKnobTab),
      new_window: boolean('Transcript link opens in new window', true, generalKnobTab),
      is_external: boolean('Share link - is external', true, generalKnobTab),
    },
    share_link: {
      text: text('Share link text', 'Share this video', generalKnobTab),
      title: text('Share link title', 'Opens in new tab', generalKnobTab),
      url: text('Share link url', 'https://example.com', generalKnobTab),
      new_window: boolean('Share link opens in new window', true, generalKnobTab),
      is_external: boolean('Share link - is external', true, generalKnobTab),
    },
  };

  return CivicVideo({
    ...generalKnobs,
  });
};
