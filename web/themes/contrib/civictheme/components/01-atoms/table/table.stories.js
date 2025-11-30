import Component from './table.twig';

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

const meta = {
  title: 'Atoms/Table',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    caption: {
      control: { type: 'text' },
    },
    caption_position: {
      control: { type: 'radio' },
      options: ['before', 'after'],
    },
    header: {
      control: { type: 'array' },
    },
    rows: {
      control: { type: 'array' },
    },
    footer: {
      control: { type: 'array' },
    },
    is_striped: {
      control: { type: 'boolean' },
    },
    is_data_table: {
      control: { type: 'boolean' },
    },
    attributes: {
      control: { type: 'text' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Table = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    caption: 'Table caption',
    caption_position: 'before',
    header: [
      'Header 1',
      'Header 2',
      'Header 3',
      'Header 4',
    ],
    rows: getRows('light'),
    footer: [
      'Footer 1',
      'Footer 2',
      'Footer 3',
      'Footer 4',
    ],
    is_striped: '',
    is_data_table: '',
    attributes: '',
    modifier_class: '',
  },
  render: (args) => Component({
    ...args,
    rows: getRows(args.theme),
  }),
};
