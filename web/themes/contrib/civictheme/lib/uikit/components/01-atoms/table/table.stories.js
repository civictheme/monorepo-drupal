// phpcs:ignoreFile
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

  const getRows = function (theme) {
    return [
      [
        `<a class="ct-link ct-theme-${theme}" href="#" title="Row 1 with link">Row 1 with link</a>`,
        'Description summary on odd row with vertical spacing',
        'Another column',
        'One more column column',
      ],
      [
        `<a class="ct-link ct-theme-${theme}" href="#" title="Row 1 with link">Row 2 with link</a>`,
        'Description summary on even row',
        'Another column',
        'One more column column',
      ],
      [
        `<a class="ct-link ct-theme-${theme}" href="#" title="Row 1 with link">Row 3 with link</a>`,
        'Description summary on odd row with vertical spacing',
        'Another column',
        'One more column column',
      ],
      [
        `<a class="ct-link ct-theme-${theme}" href="#" title="Row 1 with link">Row 5 with link</a>`,
        'Description summary on even row',
        'Another column',
        'One more column column',
      ],
      [
        'Row 5 without a link',
        'Description summary on odd row with vertical spacing',
        'Another column',
        'One more column column',
      ],
      [
        'Row 6 with larger amount of content',
        '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p><p>Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p><p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p><p>It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>',
        'Another column',
        'One more column column',
      ],
      [
        'Row 7 with small amount of content',
        '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>',
        'Another column',
        'One more column column',
      ],
    ];
  };

  const generalKnobs = {
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
    rows: boolean('With rows', true, generalKnobTab) ? true : null,
    footer: boolean('With footer', true, generalKnobTab) ? footer : [],
    is_striped: boolean('Striped', true, generalKnobTab),
    caption: text('Caption content', 'Table caption Sed porttitor lectus nibh. Curabitur aliquet quam id dui posuere blandit. Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui. Proin eget tortor risus.', generalKnobTab),
    caption_position: radios(
      'Caption position',
      {
        Before: 'before',
        After: 'after',
      },
      'before',
      generalKnobTab,
    ),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  if (generalKnobs.rows) {
    generalKnobs.rows = getRows(generalKnobs.theme);
  }

  return CivicThemeTable({
    ...generalKnobs,
  });
};
