// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';
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
    content: boolean('With content', true, generalKnobTab) ? placeholder() : '',
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

  return CivicThemePopover({
    ...generalKnobs,
  });
};
