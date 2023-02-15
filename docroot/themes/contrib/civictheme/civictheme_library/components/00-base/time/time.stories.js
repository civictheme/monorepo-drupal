// phpcs:ignoreFile
import { text } from '@storybook/addon-knobs';
import CivicThemeTime from './time.twig';
import { dateIsValid } from '../base.utils';

export default {
  title: 'Base/Time',
  parameters: {
    layout: 'centered',
  },
};

export const Time = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const generalKnobs = {
    start: text('Start', '20 Jan 2023 11:00', generalKnobTab),
    end: text('End', '21 Jan 2023 15:00', generalKnobTab),
    modifier_class: text('Additional classes', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  generalKnobs.start_iso = dateIsValid(generalKnobs.start) ? new Date(generalKnobs.start).toISOString() : null;
  generalKnobs.end_iso = dateIsValid(generalKnobs.end) ? new Date(generalKnobs.end).toISOString() : null;

  return CivicThemeTime({
    ...generalKnobs,
  });
};
