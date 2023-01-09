// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';

import CivicThemeVideo from './video-player.twig';
import { demoVideoPoster, demoVideos } from '../../00-base/base.utils';

export default {
  title: 'Molecules/Video Player',
};

export const VideoPlayer = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';
  const sourceKnobTab = 'Source';
  const transcriptLinkKnobTab = 'Transcript Link';

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
    source_type: radios('Source type', {
      File: 'file',
      Embedded: 'embedded',
      Raw: 'raw',
    }, 'file', generalKnobTab),
    title: text('Title', 'Test video', generalKnobTab),
    width: text('Width', '', generalKnobTab),
    height: text('Height', '500', generalKnobTab),
    with_transcript_link: boolean('With Transcript link', true, generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  const sourceKnobs = {};
  if (generalKnobs.source_type === 'file') {
    sourceKnobs.sources = boolean('With sources', true, sourceKnobTab) ? demoVideos() : null;
    if (sourceKnobs.sources) {
      sourceKnobs.poster = boolean('With poster', true, sourceKnobTab) ? demoVideoPoster() : null;
    }
  } else if (generalKnobs.source_type === 'embedded') {
    sourceKnobs.embedded_source = text('Embedded source', 'https://www.youtube.com/embed/C0DPdy98e4c', sourceKnobTab);
  } else {
    sourceKnobs.raw_source = boolean('With raw input', true, sourceKnobTab) ? '<iframe allowfullscreen="" frameborder="0" height="315" src="https://www.youtube.com/embed/C0DPdy98e4c" width="420"></iframe>' : null;
  }

  let transcriptLinkKnobs = {};
  if (generalKnobs.with_transcript_link) {
    transcriptLinkKnobs = {
      transcript_link: {
        text: text('Text', 'View transcript', transcriptLinkKnobTab),
        title: text('Title', 'Open transcription in a new window', transcriptLinkKnobTab),
        url: text('URL', 'https://example.com', transcriptLinkKnobTab),
        is_new_window: boolean('Open in a new window', true, transcriptLinkKnobTab),
        is_external: boolean('Is external', false, transcriptLinkKnobTab),
      },
    };
  }

  return CivicThemeVideo({
    ...generalKnobs,
    ...sourceKnobs,
    ...transcriptLinkKnobs,
  });
};
