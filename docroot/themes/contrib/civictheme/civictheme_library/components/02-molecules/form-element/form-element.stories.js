// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicThemeFormElement from './form-element.twig';
import Input from '../../01-atoms/input/input.twig';
import Select from '../../01-atoms/select/select.twig';
import CivicThemeFieldLabel from '../../01-atoms/field-label/field-label.twig';

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
    description_position: isRadioOrCheckbox ? 'after' : radios(
      'Description position',
      {
        Before: 'before',
        After: 'after',
      },
      'after',
      generalKnobTab,
    ),
    description: {
      content: text('Description', 'CivicTheme input description', generalKnobTab),
    },
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
    value: text('Value', 'CivicTheme input', inputKnobTab),
    placeholder: text('Placeholder', 'CivicTheme input', inputKnobTab),
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
    size: radios(
      'Size', {
        'Extra Large': 'extra-large',
        Large: 'large',
        Regular: 'regular',
        Small: 'small',
        'Extra Small': 'extra-small',
        None: '',
      },
      'regular',
      labelKnobTab,
    ),
    content: text('Label', 'Label for input', labelKnobTab),
    attributes: `for="input-${inputType}"`,
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

  const label = [CivicThemeFieldLabel(labelKnobs)];

  const html = CivicThemeFormElement({
    ...generalKnobs,
    type: inputType,
    label,
    children,
  });

  return `<div class="container"><div class="row"><div class="col-xxs-12">${html}</div></div></div>`;
};
