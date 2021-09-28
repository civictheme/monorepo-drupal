import {boolean, radios, text} from '@storybook/addon-knobs'
import CivicBreadcrumb from "./breadcrumb.twig";

export default {
  title: 'Molecule/Breadcrumb',
  component: CivicBreadcrumb,
}

export const Breadcrumb = (args) => CivicBreadcrumb({
  ...args,
  theme: radios(
    'Theme',
    {
      'Light': 'light',
      'Dark': 'dark',
    },
    'light',
  ),
  active_is_link: boolean('Active is link', false),
});

// Table controls.
Breadcrumb.args = {
  links: {
    'link1': {
      'text': 'Link 1',
      'url': 'http://example.com',
    },
    'link2': {
      'text': 'Link 2 title',
      'url': 'http://example.com',
    },
    'link3': {
      'text': 'Link 3 title',
      'url': 'http://example.com',
    },
    'link4': {
      'text': 'Link 3 title',
      'url': 'http://example.com',
    },
  }
}
