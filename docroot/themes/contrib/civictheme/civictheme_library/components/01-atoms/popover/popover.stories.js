// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicThemePopover from './popover.stories.twig';
import './popover';

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
    trigger_event: radios(
      'Trigger event',
      {
        Click: 'click',
        Hover: 'hover',
      },
      'click',
      generalKnobTab,
    ),
    content: boolean('With content', true, generalKnobTab) ? 'Sed porttitor lectus nibh. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec velit neque, auctor sit amet aliquam vel, ullamcorper sit amet ligula. Sed porttitor lectus nibh.' : '',
    attributes: text('Additional attributes', '', generalKnobTab),
    modifier_class: text('Additional classes', '', generalKnobTab),
  };

  return CivicThemePopover({
    ...generalKnobs,
  });
};
