import { radios, boolean, text } from '@storybook/addon-knobs'

import CivicFilterChip from './filter-chip.twig'
import './filter-chip.scss'

export default {
  title: 'Atom/Filter Chip',
}

export const FilterChip = () => CivicFilterChip({
  theme: radios(
    'Theme', {
      'Light': 'light',
      'Dark': 'dark',
    },
    'light',
  ),
  text: text('Text', 'Chip text'),
  input_id: text('Input ID', 'chip-input-1'),
  input_name: text('Input name', 'chip'),
  is_multiple: boolean('Multiple', false),
});
