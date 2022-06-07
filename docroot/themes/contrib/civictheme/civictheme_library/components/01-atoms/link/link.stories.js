import {
  boolean, radios, text,
} from '@storybook/addon-knobs';
import CivicThemeLink from './link.twig';
import { randomUrl } from '../../00-base/base.stories';

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
    text: text('Text', 'Link text', generalKnobTab),
    title: text('Title', 'Link title', generalKnobTab),
    context: text('Link context', 'Link context', generalKnobTab),
    url: text('URL', randomUrl(), generalKnobTab),
    is_external: boolean('Is external', false, generalKnobTab),
    is_new_window: boolean('Open in a new window', false, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeLink({
    ...generalKnobs,
  });
};
