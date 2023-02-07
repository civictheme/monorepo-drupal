// phpcs:ignoreFile
import { number, radios, text } from '@storybook/addon-knobs';
import CivicThemeCallout from './callout.twig';
import { getSlots, randomLinks, randomSentence } from '../../00-base/base.utils';

export default {
  title: 'Molecules/Callout',
  parameters: {
    layout: 'centered',
  },
};

export const Callout = (knobTab) => {
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
    title: text('Title', 'Callout title from knob', generalKnobTab),
    content: text('Content', randomSentence(), generalKnobTab),
    vertical_spacing: radios(
      'Vertical spacing',
      {
        None: 'none',
        Top: 'top',
        Bottom: 'bottom',
        Both: 'both',
      },
      'none',
      generalKnobTab,
    ),
    links: randomLinks(number(
      'Count of links',
      2,
      {
        range: true,
        min: 0,
        max: 10,
        step: 1,
      },
      generalKnobTab,
    )),
    modifier_class: `story-wrapper-size--large ${text('Additional class', '', generalKnobTab)}`,
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeCallout({
    ...generalKnobs,
    ...getSlots([
      'content_top',
      'content_bottom',
    ]),
  });
};
