import {
  boolean, radios, text, object,
}
  from '@storybook/addon-knobs';

import CivicSelect from './select.twig';

export default {
  title: 'Atoms/Input',
  parameters: {
    layout: 'centered',
  },
};

export const Select = () => CivicSelect({
  theme: radios(
    'Theme',
    {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
  ),
  options: object('Options', [
    { type: 'option', value: 'option1', label: 'Option 1' },
    { type: 'option', value: 'option2', label: 'Option 2' },
    { type: 'option', value: 'option3', label: 'Option 3' },
    { type: 'option', value: 'option4', label: 'Option 4' },
  ]),
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
