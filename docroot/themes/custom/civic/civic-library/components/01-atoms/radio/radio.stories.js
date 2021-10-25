import { boolean, radios, text } from '@storybook/addon-knobs';

import CivicRadio from './radio.twig';

export default {
  title: 'Atoms/Input',
  parameters: {
    layout: 'centered',
  },
};

export const Radio = () => CivicRadio({
  theme: radios(
    'Theme',
    {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
  ),
  type: 'radio',
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
