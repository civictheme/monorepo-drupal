// phpcs:ignoreFile
/**
 * CivicTheme Alert component stories.
 */

import Component from './alert.twig';

const meta = {
  title: 'Organisms/Alert',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    type: {
      control: { type: 'radio' },
      options: ['information', 'error', 'warning', 'success'],
    },
    id: {
      control: { type: 'text' },
    },
    title: {
      control: { type: 'text' },
    },
    description: {
      control: { type: 'text' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Alert = {
  parameters: {
    layout: 'fullscreen',
  },
  args: {
    theme: 'light',
    type: 'information',
    id: 'alert-1',
    title: 'Site information',
    description: 'Alert description',
    attributes: null,
    modifier_class: '',
  },
};

export const AlertApi = {
  parameters: {
    layout: 'fullscreen',
  },
  argTypes: {
    endpoint_type: {
      control: { type: 'radio' },
      options: ['default', 'updated', 'invalid'],
    },
    theme: {
      table: { disable: true },
    },
    type: {
      table: { disable: true },
    },
    id: {
      table: { disable: true },
    },
    title: {
      table: { disable: true },
    },
    description: {
      table: { disable: true },
    },
    attributes: {
      table: { disable: true },
    },
    modifier_class: {
      table: { disable: true },
    },
  },
  args: {
    endpoint_type: 'default',
  },
  render: (args) => {
    let endpoint = '';
    switch (args.endpoint_type) {
      case 'updated':
        endpoint = 'api/alerts2.json';
        break;
      case 'invalid':
        endpoint = 'api/alerts3.json';
        break;
      default:
        endpoint = 'api/alerts1.json';
    }
    return `
      <div data-component-name="ct-alerts" data-alert-endpoint="${endpoint}" data-test-path="/"></div>
      <button style="margin-top: 24px;" onclick="document.cookie = 'ct-alert-hide=;expires=Thu, 01 Jan 1970 00:00:01 GMT;'; window.location.reload();">Clear cookie</button>
    `;
  },
};
