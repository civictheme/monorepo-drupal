import { text, radios, boolean, select } from '@storybook/addon-knobs';

import CivicBasicFilter from './basic-filter.twig';
import './basic-filter.scss';

export default {
  title: 'Molecule/Basic Filter'
}

export const BasicFilter = () => CivicBasicFilter({
  theme: radios('Theme', {
    'Light': 'light',
    'Dark': 'dark'
  }, 'light', 'Theme'),
  items: [
    {
      text: text('Text', 'Basic filter 1', 'Chip 1'),
      is_active: boolean('Active', false, 'Chip 1'),
    },
    {
      text: text('Text', 'Basic filter 2', 'Chip 2'),
      is_active: boolean('Active', true, 'Chip 2'),
    },
    {
      text: text('Text', 'Basic filter 3', 'Chip 3'),
      is_active: boolean('Active', false, 'Chip 3'),
    }
  ],
});
