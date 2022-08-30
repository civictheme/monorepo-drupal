// phpcs:ignoreFile
import { addons } from '@storybook/addons';
import theme from './theme';

addons.setConfig({
  theme,
  toolbar: {
    zoom: {
      hidden: true,
    },
  },
});
