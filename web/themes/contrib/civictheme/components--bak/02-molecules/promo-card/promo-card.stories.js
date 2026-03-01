// phpcs:ignoreFile
/**
 * CivicTheme Promo Card component stories.
 */

import Component from './promo-card.twig';
import PromoCardData from './promo-card.stories.data';

const meta = {
  title: 'Molecules/List/Promo Card',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    subtitle: {
      control: { type: 'text' },
    },
    date: {
      control: { type: 'text' },
    },
    date_end: {
      control: { type: 'text' },
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
    image_over: {
      control: { type: 'text' },
    },
    is_title_click: {
      control: { type: 'boolean' },
    },
    content_top: {
      control: { type: 'text' },
    },
    content_middle: {
      control: { type: 'text' },
    },
    content_bottom: {
      control: { type: 'text' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const PromoCard = {
  parameters: {
    layout: 'centered',
  },
  args: PromoCardData.args('light'),
};
