// phpcs:ignoreFile
/**
 * CivicTheme Grid component stories.
 */

import DrupalAttribute from 'drupal-attribute';
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
    column_element: {
      control: { type: 'text' },
    },
    column_class: {
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
    auto_breakpoint: {
      control: { type: 'boolean' },
    },
    fill_width: {
      control: { type: 'boolean' },
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
    row_attributes: null,
    column_element: 'div',
    column_class: 'col',
    column_attributes: null,
    use_container: false,
    is_fluid: false,
    template_column_count: 0,
    auto_breakpoint: false,
    fill_width: false,
    attributes: null,
    modifier_class: 'row--equal-heights-content row--vertically-spaced',
  },
  render: (args) => {
    // Transform object inputs to DrupalAttribute instances
    const transformedArgs = { ...args };

    if (args.attributes && typeof args.attributes === 'object') {
      transformedArgs.attributes = new DrupalAttribute(
        Object.entries(args.attributes),
      );
    }

    if (args.row_attributes && typeof args.row_attributes === 'object') {
      transformedArgs.row_attributes = new DrupalAttribute(
        Object.entries(args.row_attributes),
      );
    }

    if (args.column_attributes && typeof args.column_attributes === 'object') {
      transformedArgs.column_attributes = new DrupalAttribute(
        Object.entries(args.column_attributes),
      );
    }

    return Component(transformedArgs);
  },
};
