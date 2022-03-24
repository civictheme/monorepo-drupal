import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicFormElement from './form-element.twig';
import Input from '../../01-atoms/input/input.twig';
import Select from '../../01-atoms/select/select.twig';
import CivicLabel from '../../01-atoms/label/label.twig';

export default {
  title: 'Organisms/Form/Form Element',
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

  // We don't allow before and after label for radio or checkbox it is always
  // after.
  const isRadioOrCheckbox = inputType === 'radio' || inputType === 'checkbox';

  const generalKnobs = {
    theme,
    label_display: isRadioOrCheckbox ? 'after' : radios(
      'Label position',
      {
        Before: 'before',
        After: 'after',
      },
      'before',
      generalKnobTab,
    ),
    description_display: isRadioOrCheckbox ? 'after' : radios(
      'Description position',
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

  const states = {
    None: 'default',
    Error: 'error',
    Success: 'success',
  };

  const inputKnobs = {
    theme,
    value: text('Value', 'Civic input', inputKnobTab),
    placeholder: text('Placeholder', 'Civic input', inputKnobTab),
    state: radios(
      'State',
      states,
      'default',
      inputKnobTab,
    ),
    attributes: `id="input-${inputType}"`,
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
    attributes: `id="input-${inputType}"`,
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
    attributes: `id="input-${inputType}"`,
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
    attributes: `id="input-${inputType}"`,
    disabled: boolean('Disabled', false, inputKnobTab),
    required: generalKnobs.required,
  };

  const labelKnobTab = 'Label';
  const labelKnobs = {
    theme,
    content: text('Label', 'Label for input', labelKnobTab),
    title_display: radios(
      'Label position',
      {
        Before: 'before',
        After: 'after',
      },
      'before',
      labelKnobTab,
    ),
    attributes: `for="input-${inputType}"`,
    required: generalKnobs.required,
  };

  const children = [];

  switch (inputType) {
    case 'radio':
      children.push(Input({
        type: inputType,
        ...radioKnobs,
      }));
      break;
    case 'checkbox':
      children.push(Input({
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

  const label = [CivicLabel(labelKnobs)];

  const html = CivicFormElement({
    ...generalKnobs,
    type: inputType,
    label,
    children,
  });

  return `<div class="container"><div class="row"><div class="col-12">${html}</div></div></div>`;
};
