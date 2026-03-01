// phpcs:ignoreFile
import Component from './background.stories.twig';
import Constants from '../../../dist/constants.json'; // eslint-disable-line import/no-unresolved

const meta = {
  title: 'Base/Background',
  component: Component,
  argTypes: {
    blend_mode: {
      control: { type: 'select' },
      options: Constants.SCSS_VARIABLES['ct-background-blend-modes'],
    },
    color: {
      control: { type: 'color' },
    },
    url: {
      control: { type: 'select' },
      options: Constants.BACKGROUNDS,
    },
  },
};

export default meta;

export const Background = {
  parameters: {
    layout: 'centered',
  },
  args: {
    url: Constants.BACKGROUNDS[Object.keys(Constants.BACKGROUNDS)[0]],
    color: '#003a4f',
    blend_mode: Constants.SCSS_VARIABLES['ct-background-blend-modes'][0],
  },
};
