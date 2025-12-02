import Component from './item-grid.twig';
import { generateItems, placeholder } from '../base.utils';

const meta = {
  title: 'Base/Item Grid',
  component: Component,
  render: (args) => {
    const items = generateItems(args.number_of_items, placeholder());
    return Component({
      ...args,
      items,
    });
  },
  argTypes: {
    column_count: {
      control: { type: 'range', min: 1, max: 4, step: 1 },
    },
    fill_width: {
      control: { type: 'boolean' },
    },
    number_of_items: {
      control: { type: 'range', min: 0, max: 7, step: 1 },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const ItemGrid = {
  args: {
    column_count: 4,
    fill_width: false,
    number_of_items: 4,
    modifier_class: '',
  },
};
