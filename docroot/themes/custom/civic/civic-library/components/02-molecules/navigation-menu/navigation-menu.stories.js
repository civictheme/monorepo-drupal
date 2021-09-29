import { text, boolean, radios, select } from '@storybook/addon-knobs'

import CivicNavigationMenu from './navigation-menu.twig'
import './navigation-menu.scss'

/**
 * Storybook Definition.
 */
export default {
  title: 'Molecule/Navigation Menu',
  component: CivicNavigationMenu,
};

export const NavigationMenu = (args) => CivicNavigationMenu({
  ...args,
  theme: radios(
    'Theme',
    {
      'Light': 'light',
      'Dark': 'dark',
    },
    'light',
  ),
  back_text: text('Section Title', 'Section Title'),
  back_url: text('URL', '')
});

// Menu controls.
NavigationMenu.args = {
  links: [
    {
      'text': 'Link 1',
      'url': 'http://test.com',
      'is_active': false,
      'expanded': false,
      'links': [
        {
          'text': 'Sub Link 1',
          'url': 'http://test.com',
          'is_active': false
        },
        {
          'text': 'Sub Link 2',
          'url': 'http://test.com',
          'is_active': true
        }
      ]
    },
    {
      'text': 'Link 2',
      'url': 'http://test.com',
      'is_active': false,
    }
  ]
}
