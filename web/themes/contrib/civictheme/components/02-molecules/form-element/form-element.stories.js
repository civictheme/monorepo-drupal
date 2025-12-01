import Component from './form-element.twig';
import Input from '../../01-atoms/input/input.twig';
import Select from '../../01-atoms/select/select.twig';
import Radio from '../../01-atoms/radio/radio.twig';
import Checkbox from '../../01-atoms/checkbox/checkbox.twig';
import { randomName } from '../../00-base/base.utils';

const meta = {
  title: 'Molecules/Form Element',
  component: Component,
  render: (args) => {
    const children = [];
    const controlArgs = {
      theme: args.theme,
      state: args.state,
      is_disabled: args.is_disabled,
      is_required: args.is_required,
    };

    switch (args.type) {
      case 'radio':
        children.push(Radio({
          ...controlArgs,
          label: 'Radio option',
          name: 'radio-name',
          value: 'radio-value',
        }));
        break;

      case 'checkbox':
        children.push(Checkbox({
          ...controlArgs,
          label: 'Checkbox option',
          name: 'checkbox-name',
          value: 'checkbox-value',
        }));
        break;

      case 'select':
        children.push(Select({
          theme: args.theme,
          state: args.state,
          is_disabled: args.is_disabled,
          options: [
            { type: 'option', value: 'option1', label: 'Option 1' },
            { type: 'option', value: 'option2', label: 'Option 2' },
            { type: 'option', value: 'option3', label: 'Option 3' },
            { type: 'option', value: 'option4', label: 'Option 4' },
          ],
        }));
        break;

      default:
        children.push(Input({
          theme: args.theme,
          type: args.type,
          value: args.value,
          placeholder: args.placeholder,
          state: args.state,
          is_disabled: args.is_disabled,
          is_required: args.is_required,
        }));
    }

    const id = randomName(5);

    const html = Component({
      theme: args.theme,
      type: args.type,
      label: args.label,
      label_display: args.label_display,
      description: args.description,
      description_display: args.description_display,
      errors: args.with_error ? 'Sample error message' : false,
      required: args.is_required,
      modifier_class: args.modifier_class,
      attributes: args.attributes,
      id,
      children,
    });

    return `<div class="container"><div class="row"><div class="col-xxs-12">${html}</div></div></div>`;
  },
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    type: {
      control: { type: 'select' },
      options: ['text', 'textarea', 'email', 'tel', 'password', 'select', 'radio', 'checkbox'],
    },
    label: {
      control: { type: 'text' },
    },
    label_display: {
      control: { type: 'radio' },
      options: ['before', 'after', 'invisible'],
    },
    description: {
      control: { type: 'text' },
    },
    description_display: {
      control: { type: 'radio' },
      options: ['before', 'after', 'invisible'],
    },
    with_error: {
      control: { type: 'boolean' },
    },
    is_required: {
      control: { type: 'boolean' },
    },
    value: {
      control: { type: 'text' },
    },
    placeholder: {
      control: { type: 'text' },
    },
    state: {
      control: { type: 'radio' },
      options: ['default', 'error', 'success'],
    },
    is_disabled: {
      control: { type: 'boolean' },
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

export const FormElement = {
  args: {
    theme: 'light',
    type: 'text',
    label: 'Label for input',
    label_display: 'before',
    description: 'Example input description',
    description_display: 'after',
    with_error: false,
    is_required: false,
    value: 'Form element value',
    placeholder: 'Form element placeholder',
    state: 'default',
    is_disabled: false,
    modifier_class: '',
    attributes: '',
  },
};
