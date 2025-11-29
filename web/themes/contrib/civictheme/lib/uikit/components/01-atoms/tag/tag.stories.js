// phpcs:ignoreFile
import {
  boolean, radios, select, text,
} from '@storybook/addon-knobs';
import {
  randomUrl,
} from '../../00-base/base.utils';

import CivicThemeTag from './tag.twig';

export default {
  title: 'Atoms/Tag',
  parameters: {
    layout: 'centered',
  },
};

export const Tag = (knobTab) => {
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
    type: radios(
      'Type',
      {
        Primary: 'primary',
        Secondary: 'secondary',
        Tertiary: 'tertiary',
      },
      'primary',
      generalKnobTab,
    ),
    content: text('Content', 'Tag content', generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  const iconKnobTab = 'Icon';
  const withIcon = boolean('With icon', false, generalKnobTab);
  const iconKnobs = {
    icon: withIcon ? select('Icon', Object.values(ICONS), Object.values(ICONS)[0], iconKnobTab) : null,
    icon_placement: withIcon ? radios(
      'Position',
      {
        Before: 'before',
        After: 'after',
      },
      'before',
      iconKnobTab,
    ) : null,
  };

  const linkKnobTab = 'Link';
  const withLink = boolean('With link', false, generalKnobTab);
  const linkKnobs = {
    url: withLink ? text('URL', randomUrl(), linkKnobTab) : null,
    is_external: withLink ? boolean('Is external', false, linkKnobTab) : null,
    is_new_window: withLink ? boolean('Open in a new window', false, linkKnobTab) : null,
  };

  return CivicThemeTag({
    ...generalKnobs,
    ...iconKnobs,
    ...linkKnobs,
  });
};
