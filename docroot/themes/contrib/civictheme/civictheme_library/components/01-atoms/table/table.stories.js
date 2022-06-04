import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicThemeTable from './table.twig';
import './table';

export default {
  title: 'Atoms/Table',
};

export const Table = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const header = [
    'Header 1',
    'Header 2',
    'Header 3',
    'Header 4',
  ];

  const footer = [
    'Footer 1',
    'Footer 2',
    'Footer 3',
    'Footer 4',
  ];

  const rows = [
    [
      "<a class='civictheme-link' href='#' title='Row 1 with link'>Row 4 with link</a>",
      'Description summary on odd row with space',
      'Another column',
      'One more column column',
    ],
    [
      "<a class='civictheme-link' href='#' title='Row 2 with link'>Row 4 with link</a>",
      'Description summary on even row',
      'Another column',
      'One more column column',
    ],
    [
      "<a class='civictheme-link' href='#' title='Row 3 with link'>Row 4 with link</a>",
      'Description summary on odd row with space',
      'Another column',
      'One more column column',
    ],
    [
      "<a class='civictheme-link' href='#' title='Row 4 with link'>Row 4 with link</a>",
      'Description summary on even row',
      'Another column',
      'One more column column',
    ],
    [
      'Row 5 without a link',
      'Description summary on odd row with space',
      'Another column',
      'One more column column',
    ],
  ];

  return CivicThemeTable({
    theme: radios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      generalKnobTab,
    ),
    header: boolean('With header', true, generalKnobTab) ? header : [],
    rows: boolean('With rows', true, generalKnobTab) ? rows : [],
    footer: boolean('With footer', true, generalKnobTab) ? footer : [],
    caption: text('Caption', 'Table caption Sed porttitor lectus nibh. Curabitur aliquet quam id dui posuere blandit. Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui. Proin eget tortor risus.', generalKnobTab),
    caption_position: radios(
      'Caption position',
      {
        Before: 'before',
        After: 'after',
      },
      'after',
      generalKnobTab,
    ),
    is_striped: boolean('Striped', true, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  });
};
