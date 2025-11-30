import Component from './grid.twig';

const meta = {
  title: 'Base/Grid',
  component: Component,
  argTypes: {
    items: {
      control: { type: 'object' },
    },
    row_element: {
      control: { type: 'text' },
    },
    row_class: {
      control: { type: 'text' },
    },
    row_attributes: {
      control: { type: 'text' },
    },
    column_element: {
      control: { type: 'text' },
    },
    column_class: {
      control: { type: 'text' },
    },
    column_attributes: {
      control: { type: 'text' },
    },
    use_container: {
      control: { type: 'boolean' },
    },
    is_fluid: {
      control: { type: 'boolean' },
    },
    template_column_count: {
      control: { type: 'number' },
    },
    fill_width: {
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

export const Grid = {
  parameters: {
    layout: 'centered',
  },
  args: {
    items: [
      '<div class="story-placeholder" contenteditable="true">Item 1</div>',
      '<div class="story-placeholder" contenteditable="true">Item 2</div>',
      '<div class="story-placeholder" contenteditable="true">Item 3</div>',
      '<div class="story-placeholder" contenteditable="true">Item 4</div>',
      '<div class="story-placeholder" contenteditable="true">Item 5</div>',
      '<div class="story-placeholder" contenteditable="true">Item 6</div>',
      '<div class="story-placeholder" contenteditable="true">Item 7</div>',
      '<div class="story-placeholder" contenteditable="true">Item 8</div>',
      '<div class="story-placeholder" contenteditable="true">Item 9</div>',
      '<div class="story-placeholder" contenteditable="true">Item 10</div>',
      '<div class="story-placeholder" contenteditable="true">Item 11</div>',
      '<div class="story-placeholder" contenteditable="true">Item 12</div>',
    ],
    row_element: 'div',
    row_class: 'row',
    row_attributes: '',
    column_element: 'div',
    column_class: 'col',
    column_attributes: '',
    use_container: true,
    is_fluid: false,
    template_column_count: 0,
    fill_width: false,
    attributes: '',
    modifier_class: '',
  },
};
