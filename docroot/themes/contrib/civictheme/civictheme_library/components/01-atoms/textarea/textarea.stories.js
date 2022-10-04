// phpcs:ignoreFile
import {
  boolean, number, radios, text,
} from '@storybook/addon-knobs';
import CivicThemeTextarea from './textarea.twig';
import { indexedString } from '../../00-base/base.stories';

export default {
  title: 'Atoms/Form control/Textarea',
  parameters: {
    layout: 'centered',
  },
};

export const Textarea = (knobTab, returnHtml = true, idx = null) => {
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
    rows: number(
      'Number of rows',
      5,
      {
        range: true,
        min: 1,
        max: 10,
        step: 1,
      },
      generalKnobTab,
    ),
    placeholder: text('Placeholder', 'Placeholder', generalKnobTab),
    is_required: boolean('Required', false, generalKnobTab),
    is_invalid: boolean('Invalid', false, generalKnobTab),
    is_disabled: boolean('Disabled', false, generalKnobTab),
    name: text('Name', indexedString('control-name', idx), generalKnobTab),
    value: text('Value', '', generalKnobTab),
    id: text('ID', indexedString('control-id', idx), generalKnobTab),
    modifier_class: `story-wrapper-size--small ${text('Additional class', '', generalKnobTab)}`,
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return returnHtml ? CivicThemeTextarea({
    ...generalKnobs,
  }) : generalKnobs;
};
