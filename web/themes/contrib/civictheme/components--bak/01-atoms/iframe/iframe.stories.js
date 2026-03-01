// phpcs:ignoreFile
/**
 * CivicTheme Iframe component stories.
 */

import Component from './iframe.twig';

const meta = {
  title: 'Atoms/Iframe',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    url: {
      control: { type: 'text' },
    },
    width: {
      control: { type: 'text' },
    },
    height: {
      control: { type: 'text' },
    },
    vertical_spacing: {
      control: { type: 'radio' },
      options: ['none', 'top', 'bottom', 'both'],
    },
    with_background: {
      control: { type: 'boolean' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Iframe = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    url: 'https://www.openstreetmap.org/export/embed.html?bbox=144.1910129785538%2C-38.33563928918572%2C146.0037571191788%2C-37.37170047141492&amp;layer=mapnik',
    width: '500',
    height: '300',
    vertical_spacing: 'none',
    with_background: false,
    modifier_class: '',
    attributes: null,
  },
};
