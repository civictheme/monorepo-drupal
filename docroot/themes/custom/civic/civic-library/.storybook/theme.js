/**
 * Custom Storybook theme.
 */
import { create } from '@storybook/theming';

import logoUrl from '../assets/logo.png';

export default create({
  base: 'light',
  brandTitle: 'Civic Library',
  brandUrl: 'https://github.com/salsadigitalauorg/civic-library',
  brandImage: logoUrl,
});
