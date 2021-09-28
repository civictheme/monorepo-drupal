import { text, boolean, radios, select } from '@storybook/addon-knobs'

import CivicNavLink from './navigation.twig'
import './navigation.scss'

export default {
  title: 'Atom/Navigation Link',
}

export const NavigationLink = () => CivicNavLink({
  theme: radios(
    'Theme',
    {
      'Light': 'light',
      'Dark': 'dark',
    },
    'light',
  ),
  text: text('Text', 'Link Text'),
  url: text('URL', 'https://www.example.com'),
  is_parent: boolean('Is Parent', false),
  expanded: boolean('Expanded', false),
  level: select('Level', {'Level 1': 1, 'Level 2': 2}, 1),
  is_active: boolean('Is Active', false),
  active_child: boolean('Active Child', false),
});

