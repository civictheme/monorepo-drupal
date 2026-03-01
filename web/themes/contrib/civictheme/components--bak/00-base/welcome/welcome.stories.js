// phpcs:ignoreFile
import Component from './welcome.stories.twig';
import Constants from '../../../dist/constants.json'; // eslint-disable-line import/no-unresolved

const meta = {
  title: 'Welcome',
  component: Component,
  argTypes: {
    logos: {
      table: {
        disable: true,
      },
    },
  },
};

export default meta;

export const Welcome = {
  parameters: {
    layout: 'fullscreen',
    html: {
      disable: true,
    },
  },
  args: {
    logos: {
      primary: {
        mobile: {
          url: Constants.LOGOS.light.civictheme.mobile,
        },
        desktop: {
          url: Constants.LOGOS.light.civictheme.desktop,
        },
      },
    },
  },
};
