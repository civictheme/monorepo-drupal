// phpcs:ignoreFile
import {
  radios, text, number,
} from '@storybook/addon-knobs';
import CivicThemePopover from './popover.stories.twig';
import './popover';
import { placeholder } from '../../00-base/base.stories';

export default {
  title: 'Atoms/Popover',
  parameters: {
    layout: 'centered',
  },
};

export const Popover = (knobTab) => {
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
    num_of_popovers: number(
      'Number of popoves',
      3,
      {
        range: true,
        min: 0,
        max: 10,
        step: 1,
      },
      generalKnobTab,
    ),
    trigger_text: text('Trigger text', 'Trigger me', generalKnobTab),
    trigger_event: radios(
      'Trigger event',
      {
        Click: 'click',
        Hover: 'hover',
      },
      'click',
      generalKnobTab,
    ),
    modifier_class: text('Additional classes', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };
  generalKnobs.contents = [];
  for (let i = 0; i < generalKnobs.num_of_popovers; i++) {
    generalKnobs.contents.push(placeholder());
  }

  return CivicThemePopover({
    ...generalKnobs,
  });
};
