// phpcs:ignoreFile
import CivicThemeTable from './table.twig';
import './table';
import { knobBoolean, knobRadios, knobText, shouldRender } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Atoms/Table',
  parameters: {
    layout: 'centered',
    storyLayoutSize: 'medium',
  },
};

export const Table = (parentKnobs = {}) => {
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
        '<p>Paragraph 1 of row 6. Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p><p>Paragraph 2 of row 6. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p><p>Paragraph 3 of row 6. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p><p>Paragraph 4 of row 6. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>',
        'Another column',
        'One more column column',
      ],
      [
        'Row 7 with small amount of content',
        '<p>Paragraph 1 of row 7. Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>',
        'Another column',
        'One more column column',
      ],
    ];
  };

  const knobs = {
    theme: knobRadios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      parentKnobs.theme,
      parentKnobs.knobTab,
    ),
    header: knobBoolean('With header', true, parentKnobs.with_header, parentKnobs.knobTab) ? header : [],
    rows: knobBoolean('With rows', true, parentKnobs.with_rows, parentKnobs.knobTab) ? true : null,
    footer: knobBoolean('With footer', true, parentKnobs.with_footer, parentKnobs.knobTab) ? footer : [],
    is_striped: knobBoolean('Striped', true, parentKnobs.is_striped, parentKnobs.knobTab),
    caption: knobText('Caption content', 'Table caption Sed porttitor lectus nibh. Curabitur aliquet quam id dui posuere blandit. Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui. Proin eget tortor risus.', parentKnobs.caption, parentKnobs.knobTab),
    caption_position: knobRadios(
      'Caption position',
      {
        Before: 'before',
        After: 'after',
      },
      'before',
      parentKnobs.caption_position,
      parentKnobs.knobTab,
    ),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  if (knobs.rows) {
    knobs.rows = getRows(knobs.theme);
  }

  return shouldRender(parentKnobs) ? CivicThemeTable(knobs) : knobs;
};
