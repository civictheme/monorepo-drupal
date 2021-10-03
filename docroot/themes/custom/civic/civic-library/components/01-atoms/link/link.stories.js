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
  url: text('URL', 'https://www.example.com', linkTab),
  new_window: boolean('Open in a new window', false, linkTab),
  is_external: boolean('Link is external', false, linkTab),
});
