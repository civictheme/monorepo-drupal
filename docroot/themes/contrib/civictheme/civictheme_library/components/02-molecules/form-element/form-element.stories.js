// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicThemeFormElement from './form-element.twig';
import Input from '../../01-atoms/input/input.twig';
import Select from '../../01-atoms/select/select.twig';
import { randomName } from '../../00-base/base.utils';
import { Radio } from '../../01-atoms/radio/radio.stories';
import { Checkbox } from '../../01-atoms/checkbox/checkbox.stories';

export default {
  title: 'Molecules/Form Element',
};

export const FormElement = () => {
  const generalKnobTab = 'General';
  const inputKnobTab = 'Input';

  const theme = radios(
    'Theme',
    {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
    generalKnobTab,
  );

  const inputType = radios(
    'Type',
    {
      Text: 'text',
      Textarea: 'textarea',
      Email: 'email',
      Tel: 'tel',
      Password: 'password',
      Select: 'select',
      Radio: 'radio',
      Checkbox: 'checkbox',
    },
    'text',
    generalKnobTab,
  );

  const generalKnobs = {
    theme,
    label: text('Label', 'Label for input', generalKnobTab),
    label_display: radios(
      'Label display',
      {
        Before: 'before',
        After: 'after',
        Invisible: 'invisible',
      },
      'before',
      generalKnobTab,
    ),
    description: text('Description', 'Example input description', generalKnobTab),
    description_display: radios(
      'Description display',
      {
        Before: 'before',
        After: 'after',
        Invisible: 'invisible',
      },
      'after',
      generalKnobTab,
    ),
    errors: boolean('With error', false, generalKnobTab) ? 'Sample error message' : false,
    required: boolean('Required', false, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  const states = {
    None: 'default',
    Error: 'error',
    Success: 'success',
  };

  const inputKnobs = {
    theme,
    value: text('Value', 'Form element value', inputKnobTab),
    placeholder: text('Placeholder', 'Form element placeholder', inputKnobTab),
    state: radios(
      'State',
      states,
      'default',
      inputKnobTab,
    ),
    disabled: boolean('Disabled', false, inputKnobTab),
    required: generalKnobs.required,
  };

  const selectKnobs = {
    theme,
    state: radios(
      'State',
      states,
      'default',
      inputKnobTab,
    ),
    disabled: boolean('Disabled', false, inputKnobTab),
    options: [
      { type: 'option', value: 'option1', label: 'Option 1' },
      { type: 'option', value: 'option2', label: 'Option 2' },
      { type: 'option', value: 'option3', label: 'Option 3' },
      { type: 'option', value: 'option4', label: 'Option 4' },
    ],
  };

  const radioKnobs = {
    theme,
    state: radios(
      'State',
      states,
      'default',
      inputKnobTab,
    ),
    disabled: boolean('Disabled', false, inputKnobTab),
    required: generalKnobs.required,
  };

  const checkboxKnobs = {
    theme,
    state: radios(
      'State',
      states,
      'default',
      inputKnobTab,
    ),
    disabled: boolean('Disabled', false, inputKnobTab),
    required: generalKnobs.required,
  };

  const children = [];

  switch (inputType) {
    case 'radio':
      children.push(Radio({
        type: inputType,
        ...radioKnobs,
      }));
      break;

    case 'checkbox':
      children.push(Checkbox({
        type: inputType,
        ...checkboxKnobs,
      }));
      break;

    case 'select':
      children.push(Select({
        ...selectKnobs,
      }));
      break;

    default:
      children.push(Input({
        type: inputType,
        ...inputKnobs,
      }));
  }

  generalKnobs.id = randomName(5);

  const html = CivicThemeFormElement({
    ...generalKnobs,
    type: inputType,
    children,
  });

  return `<div class="container"><div class="row"><div class="col-xxs-12">${html}</div></div></div>`;
};
