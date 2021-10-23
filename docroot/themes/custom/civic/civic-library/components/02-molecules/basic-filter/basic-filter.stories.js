import { boolean, radios, text } from '@storybook/addon-knobs';

import CivicBasicFilter from './basic-filter.twig';

export default {
  title: 'Molecules/Basic Filter',
  parameters: {
    layout: 'centered',
  },
};

export const BasicFilter = () => CivicBasicFilter({
  theme: radios('Theme', {
    Light: 'light',
    Dark: 'dark',
  }, 'light', 'Theme'),
  is_multiple: boolean('Multiple', false, 'Theme'),
  items: [
    {
      text: text('Text', 'Basic filter 1', 'Chip 1'),
    },
    {
      text: text('Text', 'Basic filter 2', 'Chip 2'),
    },
    {
      text: text('Text', 'Basic filter 3', 'Chip 3'),
    },
  ],
});
