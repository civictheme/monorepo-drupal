// phpcs:ignoreFile
import Component from './fonts.stories.twig';
import Constants from '../../../dist/constants.json'; // eslint-disable-line import/no-unresolved

const meta = {
  title: 'Base/Fonts',
  component: Component,
  argTypes: {
    fonts: {
      table: {
        disable: true,
      },
    },
    weights: {
      table: {
        disable: true,
      },
    },
  },
};

export default meta;

export const Fonts = {
  parameters: {
    layout: 'centered',
    html: {
      disable: true,
    },
  },
  args: {
    fonts: Object.keys({
      ...Constants.SCSS_VARIABLES['ct-fonts-default'],
      ...Constants.SCSS_VARIABLES['ct-fonts'],
    }),
    weights: Object.keys({
      ...Constants.SCSS_VARIABLES['ct-font-weights-default'],
      ...Constants.SCSS_VARIABLES['ct-font-weights'],
    }),
  },
};
