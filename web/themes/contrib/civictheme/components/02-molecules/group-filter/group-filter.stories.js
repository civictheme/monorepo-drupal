import Component from './group-filter.twig';
import GroupFilterData from './group-filter.stories.data';

const meta = {
  title: 'Molecules/List/Group Filter',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    title: {
      control: { type: 'text' },
    },
    filters: {
      control: { type: 'array' },
    },
    submit_text: {
      control: { type: 'text' },
    },
    form_attributes: {
      control: { type: 'text' },
    },
    form_hidden_fields: {
      control: { type: 'text' },
    },
    content_top: {
      control: { type: 'text' },
    },
    content_bottom: {
      control: { type: 'text' },
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

export const GroupFilter = {
  parameters: {
    layout: 'padded',
  },
  args: GroupFilterData.args('light'),
};

export const GroupFilterDark = {
  parameters: {
    layout: 'padded',
    backgrounds: {
      default: 'Dark',
    },
  },
  args: GroupFilterData.args('dark'),
};
