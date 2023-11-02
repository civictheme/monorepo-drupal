// phpcs:ignoreFile
import {
  boolean, radios, text,
} from '@storybook/addon-knobs';
import CivicThemeContentLink from './content-link.twig';
import { randomUrl } from '../../00-base/base.utils';

export default {
  title: 'Atoms/Content Link',
  parameters: {
    layout: 'centered',
  },
};

export const ContentLink = (knobTab) => {
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
    url: text('URL', randomUrl(), generalKnobTab),
    is_external: boolean('Is external', false, generalKnobTab),
    is_new_window: boolean('Open in a new window', false, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeContentLink({
    ...generalKnobs,
  });
};
