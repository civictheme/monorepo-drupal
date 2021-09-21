/**
 * Custom Storybook theme for Civic library.
 */
import { create } from '@storybook/theming';

import logoUrl from '../assets/logo.png';

export default create({
  base: 'light',
  brandTitle: 'Civic',
  brandUrl: 'https://github.com/salsadigitalauorg/civic-theme',
  brandImage: logoUrl,
});
