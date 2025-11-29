// phpcs:ignoreFile
import CivicThemeVideo from './video-player.twig';
import { demoVideoPoster, demoVideos, knobBoolean, knobRadios, knobText, shouldRender } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Molecules/Video Player',
};

export const VideoPlayer = (parentKnobs = {}) => {
  const knobs = {
    theme: knobRadios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      parentKnobs.theme,
      parentKnobs.knobTab,
    ),
    source_type: knobRadios('Source type', {
      File: 'file',
      Embedded: 'embedded',
      Raw: 'raw',
    }, 'file', parentKnobs.source_type, parentKnobs.knobTab),
    title: knobText('Title', 'Test video', parentKnobs.title, parentKnobs.knobTab),
    width: knobText('Width', '', parentKnobs.width, parentKnobs.knobTab),
    height: knobText('Height', '500', parentKnobs.height, parentKnobs.knobTab),
    with_transcript_link: knobBoolean('With Transcript link', true, parentKnobs.with_transcript_link, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
  };

  const sourceKnobs = {};
  const sourceKnobTab = 'Source';
  if (knobs.source_type === 'file') {
    sourceKnobs.sources = knobBoolean('With sources', true, parentKnobs.with_source, sourceKnobTab) ? demoVideos() : null;
    if (sourceKnobs.sources) {
      sourceKnobs.poster = knobBoolean('With poster', true, parentKnobs.with_poster, sourceKnobTab) ? demoVideoPoster() : null;
    }
  } else if (knobs.source_type === 'embedded') {
    sourceKnobs.embedded_source = knobText('Embedded source', 'https://www.youtube.com/embed/C0DPdy98e4c', parentKnobs.embedded_source, sourceKnobTab);
  } else {
    sourceKnobs.raw_source = knobBoolean('With raw input', true, parentKnobs.with_raw_input, sourceKnobTab) ? '<iframe allowfullscreen="" frameborder="0" height="315" src="https://www.youtube.com/embed/C0DPdy98e4c" width="420"></iframe>' : null;
  }

  let transcriptLinkKnobs = {};
  const transcriptLinkKnobTab = 'Transcript Link';
  if (knobs.with_transcript_link) {
    transcriptLinkKnobs = {
      transcript_link: {
        text: knobText('Text', 'View transcript', parentKnobs.transcript_link_text, transcriptLinkKnobTab),
        title: knobText('Title', 'Open transcription in a new window', parentKnobs.transcript_link_title, transcriptLinkKnobTab),
        url: knobText('URL', 'https://example.com', parentKnobs.transcript_link_url, transcriptLinkKnobTab),
        is_new_window: knobBoolean('Open in a new window', true, parentKnobs.transcript_link_is_new_window, transcriptLinkKnobTab),
        is_external: knobBoolean('Is external', false, parentKnobs.transcript_link_is_external, transcriptLinkKnobTab),
      },
    };
  }

  const combinedKnobs = { ...knobs, ...sourceKnobs, ...transcriptLinkKnobs };

  return shouldRender(parentKnobs) ? CivicThemeVideo(combinedKnobs) : combinedKnobs;
};
