import {
  boolean, radios, select, text,
} from '@storybook/addon-knobs';
import CivicLink from './link.twig';

export default {
  title: 'Atoms/Link',
  parameters: {
    layout: 'centered',
  },
};

export const Link = (knobTab) => {
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
    modifier_class: select(
      'Modifiers',
      {
        None: '',
        Visited: 'civic-link--visited',
      },
      '',
      generalKnobTab,
    ),
    text: text('Text', 'Link text', generalKnobTab),
    title: text('Title', 'Link title', generalKnobTab),
    context: text('Link context', 'Link context', generalKnobTab),
    url: text('URL', 'http://example.com', generalKnobTab),
    is_external: boolean('Is external', false, generalKnobTab),
    new_window: boolean('Open in a new window', false, generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicLink({
    ...generalKnobs,
  });
};
