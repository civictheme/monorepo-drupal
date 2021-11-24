import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicFormElement from './form-element.twig';
import Input from '../../01-atoms/input/input.twig';
import Select from '../../01-atoms/select/select.twig';
import Checkbox from '../../01-atoms/checkbox/checkbox.twig';
import Radio from '../../01-atoms/radio/radio.twig';
import CivicLabel from '../../01-atoms/label/label.twig';

export default {
  title: 'Organisms/Form/Form Element',
};

export const FormElement = () => {
  const generalKnobTab = 'General';
  const inputKnobTab = 'Input';
  const radioKnobTab = 'General';

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
      radioKnobTab,
    ),
    attributes: `id="input-${inputType}"`,
    required: generalKnobs.required,
  };

  const checkboxKnobs = {
    theme,
    state: radios(
      'State',
      states,
      'default',
      radioKnobTab,
    ),
    attributes: `id="input-${inputType}"`,
    required: generalKnobs.required,
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
    attributes: `for="input-${inputType}"`,
    required: generalKnobs.required,
  };

  const children = [];

  switch (inputType) {
    case 'radio':
      children.push(Radio(radioKnobs));
      break;
    case 'checkbox':
      children.push(Checkbox(checkboxKnobs));
      break;
    case 'select':
      children.push(Select(selectKnobs));
      break;
    default:
      children.push(Input({
        ...inputKnobs,
        type: inputType,
      }));
  }

  const label = [CivicLabel(labelKnobs)];

  const html = CivicFormElement({
    ...generalKnobs,
    type: inputType,
    label,
    children,
  });

  return `<div class="container"><div class="row"><div class="col-xs-12">${html}</div></div></div>`;
};
