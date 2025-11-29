// phpcs:ignoreFile
import {
  boolean, radios, text, select,
} from '@storybook/addon-knobs';
import CivicThemeLink from './link.twig';
import { randomUrl } from '../../00-base/base.utils';

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
    hidden_text: text('Link hidden text', 'Link hidden text', generalKnobTab),
    url: text('URL', randomUrl(), generalKnobTab),
    is_external: boolean('Is external', false, generalKnobTab),
    is_active: boolean('Is active', false, generalKnobTab),
    is_disabled: boolean('Is disabled', false, generalKnobTab),
    is_new_window: boolean('Open in a new window', false, generalKnobTab),
    with_icon: boolean('With icon', false, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  const iconKnobTab = 'Icon';
  const iconKnobs = generalKnobs.with_icon ? {
    icon_placement: radios(
      'Icon Position',
      {
        Before: 'before',
        After: 'after',
      },
      'before',
      iconKnobTab,
    ),
    icon: select('Icon', Object.values(ICONS), Object.values(ICONS)[0], iconKnobTab),
  } : null;

  return CivicThemeLink({
    ...generalKnobs,
    ...iconKnobs,
  });
};
