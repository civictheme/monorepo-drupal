// phpcs:ignoreFile
import {
  boolean, radios, text, select,
} from '@storybook/addon-knobs';
import CivicThemeLink from './link.twig';

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
    url: text('URL', 'https://example.com', generalKnobTab),
    is_external: boolean('Is external', false, generalKnobTab),
    is_new_window: boolean('Open in a new window', false, generalKnobTab),
    with_icon: boolean('With icon', false, generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
    modifier_class: text('Additional classes', '', generalKnobTab),
  };

  const iconKnobTab = 'Icon';
  const iconKnobs = {
    icon_type: radios(
      'Icon type',
      {
        'HTML element': 'html',
        'CSS class': 'css',
      },
      'html',
      iconKnobTab,
    ),
    icon_position: radios(
      'Icon position',
      {
        Before: 'before',
        After: 'after',
      },
      'before',
      iconKnobTab,
    ),
    icon: select('Icon', Object.values(ICONS), Object.values(ICONS)[0], iconKnobTab),
  };

  return CivicThemeLink({
    ...generalKnobs,
    ...iconKnobs,
  });
};
