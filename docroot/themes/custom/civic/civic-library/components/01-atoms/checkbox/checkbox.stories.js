import { boolean, radios, text } from '@storybook/addon-knobs';

import CivicCheckbox from './checkbox.twig';

export default {
  title: 'Atoms/Input',
  parameters: {
    layout: 'centered',
  },
};

export const Checkbox = () => CivicCheckbox({
  theme: radios(
    'Theme',
    {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
  ),
  type: 'checkbox',
  value: text('Value', 'Civic input'),
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
