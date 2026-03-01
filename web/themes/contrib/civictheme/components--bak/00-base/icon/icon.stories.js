// phpcs:ignoreFile
/**
 * CivicTheme Icon component stories.
 */

import Component from './icon.twig';
import Constants from '../../../dist/constants.json'; // eslint-disable-line import/no-unresolved

const meta = {
  title: 'Base/Icon',
  component: Component,
  argTypes: {
    symbol: {
      control: { type: 'select' },
      options: Constants.ICONS,
    },
    size: {
      control: { type: 'radio' },
      options: [
        'auto',
        ...Object.keys(Constants.SCSS_VARIABLES['ct-icon-sizes-default']),
        ...Object.keys(Constants.SCSS_VARIABLES['ct-icon-sizes']),
      ],
    },
    modifier_class: {
      control: { type: 'text' },
    },
    assets_dir: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Icon = {
  parameters: {
    layout: 'centered',
  },
  args: {
    symbol: Constants.ICONS[0],
    size: 'auto',
    modifier_class: '',
    attributes: null,
    assets_dir: '@civictheme/../assets',
  },
};
