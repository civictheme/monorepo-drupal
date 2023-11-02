// phpcs:ignoreFile
import {
  boolean, number, radios, text,
} from '@storybook/addon-knobs';
import {
  getSlots, randomInt, randomSentence,
  randomTags,
  randomUrl,
} from '../../00-base/base.utils';

import CivicThemeSummary from './snippet.twig';

export default {
  title: 'Molecules/Snippet',
  parameters: {
    layout: 'centered',
  },
};

export const Snippet = (knobTab) => {
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
    title: text('Title', 'Snippet name which runs across two or three lines', generalKnobTab),
    summary: text('Summary', randomSentence(randomInt(15, 25)), generalKnobTab),
    link: {
      url: text('Link URL', randomUrl(), generalKnobTab),
      is_external: boolean('Link is external', false, generalKnobTab),
      is_new_window: boolean('Open in a new window', false, generalKnobTab),
    },
    tags: randomTags(number(
      'Number of tags',
      2,
      {
        range: true,
        min: 0,
        max: 10,
        step: 1,
      },
      generalKnobTab,
    ), true),
    modifier_class: `story-wrapper-size--large ${text('Additional class', '', generalKnobTab)}`,
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeSummary({
    ...generalKnobs,
    ...getSlots([
      'content_top',
      'content_middle',
      'content_bottom',
    ]),
  });
};
