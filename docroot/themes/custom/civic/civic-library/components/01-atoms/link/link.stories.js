import {
  boolean, radios, select, text,
} from '@storybook/addon-knobs';
import CivicLink from './link.twig';
import './link.scss';

export default {
  title: 'Atom/Link',
  parameters: {
    layout: 'centered',
  },
};

const linkTab = 'General';
export const Link = () => CivicLink({
  theme: radios(
    'Theme',
    {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
    linkTab,
  ),
  modifier_class: select(
    'Modifiers',
    {
      None: '',
      Visited: 'civic-link--visited',
    },
    '',
    linkTab,
  ),
  text: text('Text', 'Link text', linkTab),
  title: text('Title', 'Link title', linkTab),
  context: text('Link context', 'Link context', linkTab),
  url: text('URL', 'http://example.com', linkTab),
  is_external: boolean('Is external', false, linkTab),
  new_window: boolean('Open in a new window', false, linkTab),
  attributes: text('Additional attributes', '', linkTab),
});
