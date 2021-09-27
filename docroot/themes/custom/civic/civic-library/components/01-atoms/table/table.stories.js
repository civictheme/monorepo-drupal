import {boolean, radios, text} from "@storybook/addon-knobs";
import CivicTable from './table.twig';

/**
 * Storybook Definition.
 */
export default {
  title: 'Atom/Table',
  component: CivicTable,
};

export const Table = (args) => CivicTable({
  ...args,
  props: [
    'Header',
    'Rows',
    'Striped',
  ],
  theme: radios(
    'Theme',
    {
      'Light': 'light',
      'Dark': 'dark',
    },
    'light',
  ),
  heading: text('Heading', 'This is table heading'),
  striped: boolean('Striped', true),
  caption: text('Caption', 'Sed porttitor lectus nibh. Curabitur aliquet quam id dui posuere blandit. Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui. Proin eget tortor risus.'),
});

// Table controls.
Table.args = {
  header: [
    'Header 1',
    'Header 2',
    'Header 3',
  ],
  rows: [
    [
      'Row 1',
      'Information about something',
      'And this is important',
    ],
    [
      'Row 2',
      'Information about something',
      'And this is important',
    ],
    [
      'Row 3',
      'Information about something',
      'And this is important',
    ],
  ]
}
