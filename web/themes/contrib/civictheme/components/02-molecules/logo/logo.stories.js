import Component from './logo.twig';
import LogoData from './logo.stories.data';

const meta = {
  title: 'Molecules/Logo',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    type: {
      control: { type: 'radio' },
      options: ['default', 'stacked', 'inline', 'inline-stacked'],
    },
    logos: {
      control: { type: 'object' },
    },
    url: {
      control: { type: 'text' },
    },
    title: {
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

export const Logo = {
  parameters: {
    layout: 'centered',
  },
  args: LogoData.args('light'),
};

export const LogoDark = {
  parameters: {
    layout: 'centered',
    backgrounds: {
      default: 'Dark',
    },
  },
  args: LogoData.args('dark'),
};
