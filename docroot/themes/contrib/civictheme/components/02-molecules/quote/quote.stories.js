import CivicThemeQuote from './quote.twig';

const meta = {
  title: 'Molecules/Quote',
  component: CivicThemeQuote,
  parameters: {
    layout: 'fullscreen',
  },
  render: (args) => CivicThemeQuote(args),
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    content: {
      control: { type: 'text' },
    },
    author: {
      control: { type: 'text' },
    },
    vertical_spacing: {
      control: { type: 'radio' },
      options: ['none', 'top', 'bottom', 'both'],
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

export const Quote = {
  args: {
    theme: 'light',
    content: 'Quote content',
    author: 'Quote author',
    vertical_spacing: 'none',
    attributes: '',
    modifier_class: '',
  },
};
