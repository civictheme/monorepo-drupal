import {
  radios, text, object,
} from '@storybook/addon-knobs';

import CivicLinkList from './link-list.twig';

export default {
  title: 'Molecule/Link List',
  parameters: {
    layout: 'centered',
  },
};

export const LinkList = () => {
  const links = [
    { title: 'Link title 1', url: '#' },
    { title: 'Link title 2', url: '#' },
    { title: 'Link title 3 long title goes over multiple line', url: '#' },
    { title: 'Link title 4', url: '#', new_window: true },
    { title: 'Link title 5', url: 'http://google.com', is_external: true },
  ];

  return CivicLinkList({
    theme: radios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
    ),
    type: radios(
      'Type',
      {
        Default: 'default',
        Inline: 'inline',
      },
      'default',
    ),
    modifier_class: text('Additional class', ''),
    links: object('Links', links),
  });
};
