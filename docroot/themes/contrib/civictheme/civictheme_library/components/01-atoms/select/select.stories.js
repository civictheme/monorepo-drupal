// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicThemeSelect from './select.twig';
import { randomSelectOptions, randomInt } from '../../00-base/base.stories';

export default {
  title: 'Atoms/Form control/Select',
  parameters: {
    layout: 'centered',
  },
};

export const Select = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';
  const numOfOptions = randomInt(3, 5);

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
    options: boolean('With options', true, generalKnobTab) ? randomSelectOptions(numOfOptions, (boolean('Options have groups', false, generalKnobTab) ? 'optgroup' : 'option')) : [],
    is_multiple: boolean('Is multiple', false, generalKnobTab),
    is_required: boolean('Required', false, generalKnobTab),
    is_invalid: boolean('Invalid', false, generalKnobTab),
    is_disabled: boolean('Disabled', false, generalKnobTab),
    name: text('Name', 'control-name', generalKnobTab),
    value: text('Value', '', generalKnobTab),
    id: text('ID', 'control-id', generalKnobTab),
    modifier_class: `story-wrapper-size--small ${text('Additional class', '', generalKnobTab)}`,
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeSelect({
    ...generalKnobs,
  });
};
