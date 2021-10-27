import {
  boolean, radios, text,
} from '@storybook/addon-knobs';

import CivicLinkList from './link-list.twig';

export default {
  title: 'Molecules/Link List',
  parameters: {
    layout: 'centered',
  },
};

export const LinkList = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const generalKnobs = {
    theme: radios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      generalKnobTab,
    ),
    title: text('Title', 'Optional list title', generalKnobTab),
    is_inline: boolean('Inline', false, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
    links: boolean('With links', true, generalKnobTab) ? [
      { title: 'Link title 1', url: '#' },
      { title: 'Link title 2', url: '#' },
      { title: 'Link title 3 long title goes over multiple lines', url: '#' },
      { title: 'Link title 4', url: '#', new_window: true },
      { title: 'Link title 5', url: 'http://google.com', is_external: true },
    ] : null,
  };

  return CivicLinkList({
    ...generalKnobs,
  });
};
