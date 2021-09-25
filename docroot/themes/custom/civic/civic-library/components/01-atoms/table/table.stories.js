import {boolean, radios, text} from "@storybook/addon-knobs";
import CivicTable from './table.twig';

/**
 * Storybook Definition.
 */
export default {
  title: 'Atom/Table'
};

export const Table = () => CivicTable({
  theme: radios(
    'Theme',
    {
      'Light': 'light',
      'Dark': 'dark',
    },
    'light',
  ),
  striped: boolean('Striped', true),
  header: text('Header', 'This is table header.'),
  caption: text('Caption', 'Sed porttitor lectus nibh. Curabitur aliquet quam id dui posuere blandit. Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui. Proin eget tortor risus.'),
});
