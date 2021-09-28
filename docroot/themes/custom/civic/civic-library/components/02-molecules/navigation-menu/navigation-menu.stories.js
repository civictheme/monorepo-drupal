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
  title: text('Title', 'Section Title'),
  caption: text('Caption', 'Sed porttitor lectus nibh. Curabitur aliquet quam id dui posuere blandit. Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui. Proin eget tortor risus.'),
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
