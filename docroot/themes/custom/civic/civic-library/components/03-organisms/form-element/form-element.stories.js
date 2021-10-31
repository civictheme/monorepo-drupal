import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicFormElement from './form-element.twig';
import Input from '../../01-atoms/input/input.twig';
import Select from '../../01-atoms/select/select.twig';
import CivicLabel from '../../01-atoms/label/label.twig';

export default {
  title: 'Organisms/Form Element',
  parameters: {
    layout: 'centered',
  },
};

export const FormElement = () => {
  const generalKnobTab = 'General';

  const inputField = radios(
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
  );

  const generalKnobs = {
    theme,
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
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
    errors: boolean('With error', false, generalKnobTab) ? 'Sample error message' : false,
    required: boolean('Required', false, generalKnobTab),
  };

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
        None: 'default',
        Error: 'error',
        Success: 'success',
      },
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
      {
        None: 'default',
        Error: 'error',
        Success: 'success',
      },
      'default',
      inputKnobTab,
    ),
    options: [
      { type: 'option', value: 'option1', label: 'Option 1' },
      { type: 'option', value: 'option2', label: 'Option 2' },
      { type: 'option', value: 'option3', label: 'Option 3' },
      { type: 'option', value: 'option4', label: 'Option 4' },
    ],
  };

  const labelKnobTab = 'Label';
  const labelKnobs = {
    theme,
    title: text('Label', 'Label for input', labelKnobTab),
    title_display: radios(
      'Label position',
      {
        Before: 'before',
        After: 'after',
      },
      'before',
      labelKnobTab,
    ),
    required: generalKnobs.required,
  };

  const children = [];
  if (inputField === 'input') {
    children.push(Input(inputKnobs));
  }

  if (inputField === 'select') {
    children.push(Select(selectKnobs));
  }

  const label = [CivicLabel(labelKnobs)];

  return CivicFormElement({
    ...generalKnobs,
    label,
    children,
  });
};
