// phpcs:ignoreFile
import {
  boolean, radios, text, select,
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
    hidden_text: text('Link hidden text', 'Link hidden text', generalKnobTab),
    url: text('URL', randomUrl(), generalKnobTab),
    is_external: boolean('Is external', false, generalKnobTab),
    is_new_window: boolean('Open in a new window', false, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  const iconKnobTab = 'Icon';
  const withIcon = boolean('With icon', false, generalKnobTab);
  const iconKnobs = withIcon ? {
    icon_type: withIcon ? radios(
      'Icon Type',
      {
        'HTML element': 'html',
        'CSS class': 'css',
      },
      'html',
      iconKnobTab,
    ) : null,
    icon_placement: withIcon ? radios(
      'Icon Position',
      {
        Before: 'before',
        After: 'after',
      },
      'before',
      iconKnobTab,
    ) : null,
    icon: withIcon ? select('Icon', Object.values(ICONS), Object.values(ICONS)[0], iconKnobTab) : null,
  } : null;

  return CivicThemeLink({
    ...generalKnobs,
    ...iconKnobs,
  });
};
