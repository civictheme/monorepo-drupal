// phpcs:ignoreFile
import CivicThemeVideo from './video.twig';
import { demoVideoPoster, demoVideos, knobBoolean, knobOptions, knobRadios, knobText, shouldRender } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Atoms/Video',
  parameters: {
    layout: 'centered',
  },
};

export const Video = (parentKnobs = {}) => {
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
    has_controls: knobBoolean('Has controls', true, parentKnobs.has_controls, parentKnobs.knobTab),
    poster: knobBoolean('Has poster', false, parentKnobs.poster, parentKnobs.knobTab) ? demoVideoPoster() : null,
    width: knobText('Width', '', parentKnobs.width, parentKnobs.knobTab),
    height: knobText('Height', '', parentKnobs.height, parentKnobs.knobTab),
    fallback_text: knobText('Fallback text', 'Your browser doesn\'t support HTML5 video tag.', parentKnobs.fallback_text, parentKnobs.knobTab),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  const sources = demoVideos();
  const sourcesOptions = {};
  for (const i in sources) {
    sourcesOptions[sources[i].type.substr('video/'.length).toUpperCase()] = sources[i].type;
  }
  const optValues = knobOptions('Sources', sourcesOptions, Object.values(sourcesOptions), { display: 'check' }, parentKnobs.sources, 'Sources');
  const sourcesKnobs = {
    sources: sources.filter((x) => optValues.includes(x.type)),
  };

  const combinedKnobs = { ...knobs, ...sourcesKnobs };

  return shouldRender(parentKnobs) ? CivicThemeVideo(combinedKnobs) : combinedKnobs;
};
