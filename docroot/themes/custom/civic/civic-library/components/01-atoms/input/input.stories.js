import { boolean, radios, text } from '@storybook/addon-knobs';

import CivicInput from './input.twig';
import './input.scss';

export default {
  title: 'Atom/Input',
  parameters: {
    layout: 'centered',
  },
};

export const Input = () => CivicInput({
  theme: radios(
    'Theme',
    {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
  ),
  type: radios(
    'Type',
    {
      Text: 'text',
      Textarea: 'textarea',
      Email: 'email',
      Tel: 'tel',
      Password: 'password',
    },
    'text',
  ),
  value: text('Value', 'Civic input'),
  placeholder: text('Placeholder', 'Civic input'),
  autocomplete: boolean('Autocomplete', false),
  label: text('Label', 'Civic input label'),
  state: radios(
    'State',
    {
      None: 'none',
      Error: 'error',
      Success: 'success',
    },
    'none',
  ),
  disabled: boolean('Disabled', false),
  required: boolean('Required', false),
  modifier_class: text('Additional class', ''),
  attributes: text('Additional attributes', ''),
});
