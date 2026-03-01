// phpcs:ignoreFile
/**
 * CivicTheme Fast Fact Card component stories.
 */

// phpcs:ignoreFile
import Component from './fast-fact-card.twig';
import FastFactCardData from './fast-fact-card.stories.data';

const meta = {
  title: 'Molecules/List/Fast Fact Card',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    title: {
      control: { type: 'text' },
    },
    summary: {
      control: { type: 'text' },
    },
    link: {
      control: { type: 'object' },
    },
    image: {
      control: { type: 'object' },
    },
    is_title_click: {
      control: { type: 'boolean' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const FastFactCard = {
  parameters: {
    layout: 'centered',
  },
  args: FastFactCardData.args('light'),
};
