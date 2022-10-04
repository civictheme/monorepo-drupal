// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicThemeSelect from './select.twig';
import { generateSelectItems, indexedString } from '../../00-base/base.stories';

export default {
  title: 'Atoms/Form control/Select',
  parameters: {
    layout: 'centered',
  },
};

export const Select = (knobTab, returnHtml = true, idx = null) => {
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
    items: boolean('With options', true, generalKnobTab) ? generateSelectItems(5, (boolean('Options have groups', false, generalKnobTab) ? 'optgroup' : 'option')) : [],
    is_multiple: boolean('Is multiple', false, generalKnobTab),
    is_required: boolean('Required', false, generalKnobTab),
    is_invalid: boolean('Invalid', false, generalKnobTab),
    is_disabled: boolean('Disabled', false, generalKnobTab),
    name: text('Name', indexedString('control-name', idx), generalKnobTab),
    value: text('Value', '', generalKnobTab),
    id: text('ID', indexedString('control-id', idx), generalKnobTab),
    modifier_class: `story-wrapper-size--small ${text('Additional class', '', generalKnobTab)}`,
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return returnHtml ? CivicThemeSelect({
    ...generalKnobs,
  }) : generalKnobs;
};
