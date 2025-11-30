// phpcs:ignoreFile
import { knobBoolean, knobNumber, knobRadios, knobText, randomInt, randomSentence, randomTags, randomUrl, shouldRender, slotKnobs } from '../../00-base/storybook/storybook.utils';

import CivicThemeSummary from './snippet.twig';

export default {
  title: 'Molecules/List/Snippet',
  parameters: {
    layout: 'centered',
    storyLayoutSize: 'large',
  },
};

export const Snippet = (parentKnobs = {}) => {
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
    title: knobText('Title', 'Snippet name which runs across two or three lines', parentKnobs.title, parentKnobs.knobTab),
    summary: knobText('Summary', randomSentence(randomInt(15, 25)), parentKnobs.summary, parentKnobs.knobTab),
    link: {
      url: knobText('Link URL', randomUrl(), parentKnobs.link_url, parentKnobs.knobTab),
      is_external: knobBoolean('Link is external', false, parentKnobs.link_is_external, parentKnobs.knobTab),
      is_new_window: knobBoolean('Open in a new window', false, parentKnobs.link_is_new_window, parentKnobs.knobTab),
    },
    tags: randomTags(knobNumber(
      'Number of tags',
      2,
      {
        range: true,
        min: 0,
        max: 10,
        step: 1,
      },
      parentKnobs.number_of_tags,
      parentKnobs.knobTab,
    ), true),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  return shouldRender(parentKnobs) ? CivicThemeSummary({
    ...knobs,
    ...slotKnobs([
      'content_top',
      'content_middle',
      'content_bottom',
    ]),
  }) : knobs;
};
