// Documentation on theming Storybook: https://storybook.js.org/docs/configurations/theming/

import { create } from '@storybook/theming';

export default create({
  base: 'light',
  // Branding
  brandTitle: 'Civic theme',
  brandUrl: 'https://salsadigital.com.au/',
  brandImage:
    '../logo.png',
});
