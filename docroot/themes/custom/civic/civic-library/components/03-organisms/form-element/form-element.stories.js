import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicFormElement from './form-element.twig';
import Input from '../../01-atoms/input/input.twig';
import Select from '../../01-atoms/select/select.twig';
import Label from '../../01-atoms/label/label.twig';

export default {
  title: 'Organisms/Form Element',
  parameters: {
    layout: 'centered',
  },
};

export const FormElement = () => {

  const generalKnobTab = 'General';

  const input_field = radios(
    'Type',
    {
      Input: 'input',
      Select: 'select',
    },
    'input',
    generalKnobTab,
  );

  const theme = radios(
    'Theme',
    {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
    generalKnobTab,
  )

  const generalKnobs = {
    theme,
    label: text('Label', 'Civic input label', generalKnobTab),
    label_display: radios(
      'Label position',
      {
        Before: 'before',
        After: 'after',
      },
      'before',
      generalKnobTab,
    ),
    description_display: radios(
      'description position',
      {
        Before: 'before',
        After: 'after',
      },
      'after',
      generalKnobTab,
    ),
    description: {
      content: text('Description', 'Civic input description', generalKnobTab),
    },
  }

  const inputKnobTab = 'Input';

  const inputKnobs = {
    theme,
    type: radios(
      'Type',
      {
        Text: 'text',
        Textarea: 'textarea',
        Email: 'email',
        Tel: 'tel',
        Password: 'password',
        Radio: 'radio',
        Checkbox: 'checkbox',
      },
      'text',
      inputKnobTab,
    ),
    value: text('Value', 'Civic input', inputKnobTab),
    placeholder: text('Placeholder', 'Civic input', inputKnobTab),
    state: radios(
      'State',
      {
        None: 'none',
        Error: 'error',
        Success: 'success',
      },
      'none',
      inputKnobTab,
    ),
    disabled: boolean('Disabled', false, inputKnobTab),
  }

  const selectKnobs = {
    theme,
    state: radios(
      'State',
      {
        None: 'none',
        Error: 'error',
        Success: 'success',
      },
      'none',
      inputKnobTab,
    ),
    options: [
      { 'type': 'option', 'value': 'option1', 'label': 'Option 1' },
      { 'type': 'option', 'value': 'option2', 'label': 'Option 2' },
      { 'type': 'option', 'value': 'option3', 'label': 'Option 3' },
      { 'type': 'option', 'value': 'option4', 'label': 'Option 4' },
    ],
  }

  const children = [];
  if (input_field == 'input') {
    children.push(Input(inputKnobs));
  }

  if (input_field == 'select') {
    children.push(Select(selectKnobs));
  }

  return CivicFormElement({
    ...generalKnobs,
    children,
  });
}
