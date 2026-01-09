// phpcs:ignoreFile
// eslint-disable-next-line import/no-unresolved
import { addons } from 'storybook/manager-api';
import theme from './theme';

addons.setConfig({
  theme,
});
