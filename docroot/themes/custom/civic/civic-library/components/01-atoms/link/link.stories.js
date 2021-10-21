import {
  boolean, radios, select, text,
} from '@storybook/addon-knobs';
import CivicLink from './link.twig';

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
  ),
  modifier_class: select(
    'Modifiers',
    {
      None: '',
      Visited: 'civic-link--visited',
    },
    '',
  ),
  text: text('Text', 'Link Text', linkTab),
  title: text('Title', 'Link title', linkTab),
  context: text('Link context', 'Link context', linkTab),
  url: text('URL', 'http://example.com', linkTab),
  new_window: boolean('Open in a new window', false, linkTab),
  is_external: boolean('Link is external', false, linkTab),
  attributes: text('Additional attributes', '', linkTab),
});
