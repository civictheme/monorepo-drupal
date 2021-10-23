import {
  boolean, object, radios, text,
} from '@storybook/addon-knobs';

import CivicLinkList from './link-list.twig';

export default {
  title: 'Molecules/Link List',
  parameters: {
    layout: 'centered',
  },
};

export const LinkList = () => CivicLinkList({
  theme: radios(
    'Theme',
    {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
  ),
  title: text('Title', 'Optional list title'),
  is_inline: boolean('Inline', false),
  modifier_class: text('Additional class', ''),
  attributes: text('Additional attributes', ''),
  links: object('Links', [
    { title: 'Link title 1', url: '#' },
    { title: 'Link title 2', url: '#' },
    { title: 'Link title 3 long title goes over multiple lines', url: '#' },
    { title: 'Link title 4', url: '#', new_window: true },
    { title: 'Link title 5', url: 'http://google.com', is_external: true },
  ]),
});
