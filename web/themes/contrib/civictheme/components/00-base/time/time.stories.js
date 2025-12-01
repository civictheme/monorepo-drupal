import Component from './time.twig';
import { dateIsValid } from '../base.utils';

const meta = {
  title: 'Base/Time',
  component: Component,
  render: (args) => {
    const start_iso = dateIsValid(args.start) ? new Date(args.start).toISOString() : null;
    const end_iso = dateIsValid(args.end) ? new Date(args.end).toISOString() : null;
    return Component({
      ...args,
      start_iso,
      end_iso,
    });
  },
  argTypes: {
    start: {
      control: { type: 'text' },
    },
    end: {
      control: { type: 'text' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
    attributes: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Time = {
  parameters: {
    layout: 'centered',
  },
  args: {
    start: '20 Jan 2023 11:00',
    end: '21 Jan 2023 15:00',
    modifier_class: '',
    attributes: '',
  },
};
