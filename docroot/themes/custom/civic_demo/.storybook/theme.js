/**
 * Custom Storybook theme.
 */
import { create } from '@storybook/theming';

import logoUrl from '../assets/logo.png';

export default create({
  base: 'light',
  brandTitle: 'Civic Demo',
  brandUrl: 'https://github.com/salsadigitalauorg/civic',
  brandImage: logoUrl,
});
