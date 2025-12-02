import Component from './fieldset.twig';
import { randomFormElements } from '../../00-base/base.utils';

const meta = {
  title: 'Atoms/Fieldset',
  component: Component,
  render: (args) => {
    const html = Component({
      theme: args.theme,
      legend: args.legend,
      description: args.description,
      required: args.required,
      modifier_class: args.modifier_class,
      children: randomFormElements(args.number_of_elements, args.theme, true).join(''),
    });

    return `<div class="container"><div class="row"><div class="col-xxs-12">${html}</div></div></div>`;
  },
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    legend: {
      control: { type: 'text' },
    },
    description: {
      control: { type: 'text' },
    },
    required: {
      control: { type: 'boolean' },
    },
    number_of_elements: {
      control: { type: 'range', min: 0, max: 10, step: 1 },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Fieldset = {
  args: {
    theme: 'light',
    legend: 'Fieldset legend',
    description: 'Fieldset example description',
    required: true,
    number_of_elements: 1,
    modifier_class: '',
  },
};
