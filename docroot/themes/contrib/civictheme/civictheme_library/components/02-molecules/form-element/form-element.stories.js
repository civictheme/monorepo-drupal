// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicThemeFormElement from './form-element.twig';
import { Select } from '../../01-atoms/select/select.stories';
import { Textfield } from '../../01-atoms/textfield/textfield.stories';
import { Textarea } from '../../01-atoms/textarea/textarea.stories';
import { Checkbox } from '../../01-atoms/checkbox/checkbox.stories';
import { CheckboxGroup } from '../../01-atoms/checkbox-group/checkbox-group.stories';
import { RadioGroup } from '../../01-atoms/radio-group/radio-group.stories';

export default {
  title: 'Molecules/Form Element',
  parameters: {
    layout: 'centered',
  },
};

export const FormElement = () => {
  const generalKnobTab = 'General';
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
    type: radios(
      'Type',
      {
        Textfield: 'textfield',
        Textarea: 'textarea',
        Select: 'select',
        'Radio Group': 'radio-group',
        Checkbox: 'checkbox',
        'Checkbox Group': 'checkbox-group',
        Other: 'other',
      },
      'textfield',
      generalKnobTab,
    ),
    label: text('Label', 'Field label', generalKnobTab),
    description: text('Description', 'Field description that spans on the multiple lines to test vertical checkbox alignment', generalKnobTab),
    name: text('Name', 'control-name', generalKnobTab),
    value: text('Value', '', generalKnobTab),
    id: text('ID', 'control-id', generalKnobTab),
    is_required: boolean('Required', false, generalKnobTab),
    is_disabled: boolean('Disabled', false, generalKnobTab),
    is_invalid: boolean('Is invalid', false, generalKnobTab),
    message: text('Message', 'Field message that spans on the multiple lines to test vertical checkbox alignment', generalKnobTab),
    modifier_class: `story-wrapper-size--medium ${text('Additional class', '', generalKnobTab)}`,
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  generalKnobs.element = {};

  const elementKnobTab = 'Element';
  let elementKnobs = {};

  switch (generalKnobs.type) {
    case 'textfield':
      elementKnobs = Textfield(elementKnobTab, false);
      break;

    case 'textarea':
      elementKnobs = Textarea(elementKnobTab, false);
      break;

    case 'select':
      elementKnobs = Select(elementKnobTab, false);
      break;

    case 'checkbox':
      elementKnobs = Checkbox(elementKnobTab, false);
      break;

    case 'checkbox-group':
      elementKnobs = CheckboxGroup(elementKnobTab, false);
      break;

    case 'radio-group':
      elementKnobs = RadioGroup(elementKnobTab, false);
      break;

    default:
      elementKnobs = Textfield(elementKnobTab, false);
  }

  return CivicThemeFormElement({
    ...generalKnobs,
    element: elementKnobs,
  });
};
