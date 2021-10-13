import { radios, boolean, text } from '@storybook/addon-knobs';

import CivicFilterChip from './filter-chip.twig';
import './filter-chip.scss';

export default {
  title: 'Atom/Filter Chip',
  parameters: {
    layout: 'centered',
  },
};

export const FilterChip = () => CivicFilterChip({
  theme: radios(
    'Theme', {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
  ),
  text: text('Text', 'Filter Chip text'),
  id: text('Input ID', 'filter-chip-1'),
  name: text('Input name', 'chip'),
  is_multiple: boolean('Is multiple', false),
  modifier_class: text('Additional class', ''),
  attributes: text('Additional attributes', ''),
});
