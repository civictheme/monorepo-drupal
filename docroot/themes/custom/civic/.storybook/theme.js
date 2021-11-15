/**
 * Custom Storybook theme.
 */
import { create } from '@storybook/theming';

import logoUrl from '../assets/logos/civic_logo_desktop_light.png';

export default create({
  base: 'light',
  brandTitle: 'Civic',
  brandUrl: 'https://github.com/salsadigitalauorg/civic-theme',
  brandImage: logoUrl,
});
