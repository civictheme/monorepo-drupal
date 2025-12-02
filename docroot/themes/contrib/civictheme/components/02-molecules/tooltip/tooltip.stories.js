import Component from './tooltip.twig';
import Constants from '../../../dist/constants.json'; // eslint-disable-line import/no-unresolved

const meta = {
  title: 'Molecules/Tooltip',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    position: {
      control: { type: 'radio' },
      options: ['auto', 'left', 'right', 'top', 'bottom'],
    },
    icon: {
      control: { type: 'select' },
      options: Constants.ICONS,
    },
    icon_size: {
      control: { type: 'select' },
      options: [
        ...Object.keys(Constants.SCSS_VARIABLES['ct-icon-sizes-default']),
        ...Object.keys(Constants.SCSS_VARIABLES['ct-icon-sizes']),
      ],
    },
    modifier_class: {
      control: { type: 'text' },
    },
    attributes: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Tooltip = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    position: 'auto',
    icon: Constants.ICONS[0],
    icon_size: Object.keys(Constants.SCSS_VARIABLES['ct-icon-sizes-default'])[2],
    title: 'Toggle tooltip display',
    content: 'Ullamco incididunt laborum aliquip.',
    modifier_class: '',
    attributes: '',
  },
};
