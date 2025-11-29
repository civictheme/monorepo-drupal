// phpcs:ignoreFile
import {
  radios, text,
} from '@storybook/addon-knobs';

import CivicThemePopover from './popover.twig';
import { getSlots, placeholder } from '../../00-base/base.utils';

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
    trigger: {
      text: text('Trigger text', 'Click me', generalKnobTab),
      url: text('Trigger URL', null, generalKnobTab),
    },
    content: placeholder(),
    modifier_class: text('Additional classes', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemePopover({
    ...generalKnobs,
    ...getSlots([
      'content_top',
      'content_bottom',
    ]),
  });
};
