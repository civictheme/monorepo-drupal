// phpcs:ignoreFile
import {
  boolean, number, radios, text,
} from '@storybook/addon-knobs';
import CivicThemeCheckboxGroup from './checkbox-group.twig';
import { generateInputItems } from '../../00-base/base.stories';

export default {
  title: 'Atoms/Form control/Checkbox Group',
  parameters: {
    layout: 'centered',
  },
};

export const CheckboxGroup = (knobTab, returnHtml = true) => {
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
    direction: radios(
      'Direction',
      {
        Horizontal: 'horizontal',
        Vertical: 'vertical',
      },
      'vertical',
      generalKnobTab,
    ),
    items: generateInputItems(number(
      'Items count',
      5,
      {
        range: true,
        min: 0,
        max: 10,
        step: 1,
      },
      generalKnobTab,
    )),
    is_invalid: boolean('Invalid', false, generalKnobTab),
    is_disabled: boolean('Disabled', false, generalKnobTab),
    modifier_class: `story-wrapper-size--small ${text('Additional class', '', generalKnobTab)}`,
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return returnHtml ? CivicThemeCheckboxGroup({
    ...generalKnobs,
  }) : generalKnobs;
};
