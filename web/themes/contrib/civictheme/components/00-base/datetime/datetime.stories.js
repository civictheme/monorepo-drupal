// phpcs:ignoreFile
import CivicThemeTimestamp from './datetime.twig';
import { dateIsValid, knobText, shouldRender } from '../storybook/storybook.utils';

export default {
  title: 'Base/Utilities/Datetime',
  parameters: {
    layout: 'centered',
  },
};

export const Datetime = (parentKnobs = {}) => {
  const knobs = {
    start: knobText('Start', '20 Jan 2023 11:00', parentKnobs.start, parentKnobs.knobTab),
    end: knobText('End', '21 Jan 2023 15:00', parentKnobs.end, parentKnobs.knobTab),
    modifier_class: knobText('Additional classes', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  knobs.start_iso = dateIsValid(knobs.start) ? new Date(knobs.start).toISOString() : null;
  knobs.end_iso = dateIsValid(knobs.end) ? new Date(knobs.end).toISOString() : null;

  return shouldRender(parentKnobs) ? CivicThemeTimestamp(knobs) : knobs;
};
