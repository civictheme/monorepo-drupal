/**
 * Custom Storybook theme.
 */
import { create } from '@storybook/theming';

import logoUrl from '../assets/logos/civic_demo_logo_desktop_light.png';

export default create({
  base: 'light',
  brandTitle: 'Civic Demo',
  brandUrl: 'https://github.com/salsadigitalauorg/civic',
  brandImage: logoUrl,
});
