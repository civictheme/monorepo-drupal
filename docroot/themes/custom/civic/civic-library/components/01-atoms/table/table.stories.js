import {
  boolean,
  radios,
  text,
} from '@storybook/addon-knobs';
import CivicTable from './table.twig';
import './table.scss';

/**
 * Storybook Definition.
 */
export default {
  title: 'Atom/Table',
};

export const Table = () => {
  const generalKnobTab = 'General';

  // Table controls.
  const header = [
    'Header 1',
    'Header 2',
    'Header 3',
  ];

  const rows = [
    [
      "<a class='civic-link' href='#' title='Row 4 with link'>Row 4 with link</a>",
      'Information about something',
      'And this is important',
    ],
    [
      "<a class='civic-link' href='#' title='Row 4 with link'>Row 4 with link</a>",
      'Information about something',
      'And this is important',
    ],
    [
      "<a class='civic-link' href='#' title='Row 4 with link'>Row 4 with link</a>",
      'Information about something',
      'And this is important',
    ],
    [
      "<a class='civic-link' href='#' title='Row 4 with link'>Row 4 with link</a>",
      'Information about something',
      'And this is important',
    ],
    [
      'Row 5',
      'Information about something',
      'And this is important',
    ],
  ];

  return CivicTable({
    theme: radios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      generalKnobTab,
    ),
    heading: text('Heading', 'This is table heading', generalKnobTab),
    striped: boolean('Striped', true, generalKnobTab),
    caption: text('Caption', 'Sed porttitor lectus nibh. Curabitur aliquet quam id dui posuere blandit. Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui. Proin eget tortor risus.', generalKnobTab),
    header,
    rows,
  });
};
