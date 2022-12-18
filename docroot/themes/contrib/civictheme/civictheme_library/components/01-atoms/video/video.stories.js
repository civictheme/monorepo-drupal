// phpcs:ignoreFile
import {
  boolean, optionsKnob, radios, text,
} from '@storybook/addon-knobs';

import CivicThemeVideo from './video.twig';
import { demoVideoPoster, demoVideos } from '../../00-base/base.utils';

export default {
  title: 'Atoms/Video',
  parameters: {
    layout: 'centered',
  },
};

export const Video = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';
  const sourcesKnobTab = 'Sources';

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
    has_controls: boolean('Has controls', true, generalKnobTab),
    poster: boolean('Has poster', false, generalKnobTab) ? demoVideoPoster() : null,
    width: text('Width', '', generalKnobTab),
    height: text('Height', '', generalKnobTab),
    fallback_text: text('Fallback text', 'Your browser doesn\'t support HTML5 video tag.', generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  const sources = demoVideos();
  const sourcesOptions = {};
  for (const i in sources) {
    sourcesOptions[sources[i].type.substr('video/'.length).toUpperCase()] = sources[i].type;
  }
  const optionsObj = {
    display: 'check',
  };
  const optValues = optionsKnob('Sources', sourcesOptions, Object.values(sourcesOptions), optionsObj, sourcesKnobTab);
  const sourcesKnobs = {
    sources: sources.filter((x) => optValues.includes(x.type)),
  };

  return CivicThemeVideo({
    ...generalKnobs,
    ...sourcesKnobs,
  });
};
