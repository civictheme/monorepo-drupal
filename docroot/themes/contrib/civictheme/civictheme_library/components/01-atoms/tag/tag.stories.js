// phpcs:ignoreFile
import {
  boolean, radios, select, text,
} from '@storybook/addon-knobs';
import {
  randomUrl,
} from '../../00-base/base.stories';

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
      'Theme', {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      generalKnobTab,
    ),
    is_alt: boolean('Alternative colors', false, generalKnobTab),
    text: text('Text', 'Tag text', generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  const iconKnobTab = 'Icon';
  const { icons } = ICONS;
  const withIcon = boolean('With icon', false, iconKnobTab);
  const iconKnobs = {
    icon: withIcon ? select('Icon', icons, 'business_calendar', iconKnobTab) : null,
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
  const withLink = boolean('With link', false, linkKnobTab);
  const linkKnobs = {
    url: withLink ? text('URL', randomUrl(), linkKnobTab) : null,
    is_new_window: withLink ? boolean('Open in a new window', false, linkKnobTab) : null,
  };

  return CivicThemeTag({
    ...generalKnobs,
    ...iconKnobs,
    ...linkKnobs,
  });
};
