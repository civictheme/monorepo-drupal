import {
  boolean, radios, select, text,
} from '@storybook/addon-knobs';

import CivicTag from './tag.twig';
import './tag.scss';

export default {
  title: 'Atom/Tag',
  parameters: {
    layout: 'centered',
  },
};

export const Tag = () => {
  const generalKnobTab = 'General';
  const generalKnobs = {
    theme: radios(
      'Theme', {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      generalKnobTab,
    ),
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

  return CivicTag({
    ...generalKnobs,
    ...iconKnobs,
  });
};
