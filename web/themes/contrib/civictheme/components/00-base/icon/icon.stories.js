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
    alt: {
      control: { type: 'text' },
    },
    size: {
      control: { type: 'radio' },
      options: [
        'auto',
        ...Object.keys(Constants.SCSS_VARIABLES['ct-icon-sizes-default']),
        ...Object.keys(Constants.SCSS_VARIABLES['ct-icon-sizes']),
      ],
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

export const Icon = {
  parameters: {
    layout: 'centered',
  },
  args: {
    symbol: Constants.ICONS[0],
    alt: 'Icon alt text',
    size: 'auto',
    modifier_class: '',
    attributes: '',
  },
};
