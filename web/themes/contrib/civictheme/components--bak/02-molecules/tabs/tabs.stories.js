// phpcs:ignoreFile
/**
 * CivicTheme Tabs component stories.
 */

import DrupalAttribute from 'drupal-attribute';
import Component from './tabs.twig';

const meta = {
  title: 'Molecules/Tabs',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    panels: {
      control: { type: 'array' },
    },
    links: {
      control: { type: 'array' },
    },
    vertical_spacing: {
      control: { type: 'radio' },
      options: ['none', 'top', 'bottom', 'both'],
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Tabs = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    panels: [
      {
        title: 'Panel title',
        content: 'Panel content',
        id: 'panel-1',
        is_selected: true,
      },
      {
        title: 'Panel title 2',
        content: 'Panel content 2',
        id: 'panel-2',
        is_selected: false,
      },
    ],
    links: [
      {
        text: 'Link text',
        url: 'https://example.com',
        is_new_window: false,
        is_external: false,
        modifier_class: '',
        attributes: new DrupalAttribute([
          ['id', 'panel-1-tab'],
        ]),
      },
      {
        text: 'Link text 2',
        url: 'https://example.com',
        is_new_window: false,
        is_external: false,
        modifier_class: '',
        attributes: new DrupalAttribute([
          ['id', 'panel-2-tab'],
        ]),
      },
    ],
    vertical_spacing: 'none',
    attributes: null,
    modifier_class: '',
  },
};
