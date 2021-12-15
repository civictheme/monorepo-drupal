import {
  boolean, radios, select, text,
} from '@storybook/addon-knobs';

import CivicButton from './button.twig';

export default {
  title: 'Atoms/Button',
  parameters: {
    layout: 'centered',
  },
};

export const Button = (knobTab) => {
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
    kind: radios(
      'Kind', {
        Button: 'button',
        Link: 'link',
        Reset: 'reset',
        Submit: 'submit',
        None: '',
      },
      'button',
      generalKnobTab,
    ),
    type: radios(
      'Type', {
        Primary: 'primary',
        Secondary: 'secondary',
        Tertiary: 'tertiary',
        None: '',
      },
      'primary',
      generalKnobTab,
    ),
    size: radios(
      'Size', {
        Large: 'large',
        Regular: 'regular',
        Small: 'small',
        None: '',
      },
      'regular',
      generalKnobTab,
    ),
    text: text('Text', 'Button Text', generalKnobTab),
    url: text('URL (applies to button kind "link")', 'http://example.com', generalKnobTab),
    new_window: boolean('Open in a new window (applies to button kind "link")', false, generalKnobTab),
    disabled: boolean('Disabled', false, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  const iconKnobTab = 'Icon';
  const { icons } = ICONS;
  const withIcon = boolean('With icon', false, iconKnobTab);
  const iconKnobs = {
    icon: withIcon ? select('Icon', icons, icons[0], iconKnobTab) : null,
    icon_placement: withIcon ? radios(
      'Position',
      {
        Before: 'before',
        After: 'after',
      },
      'after',
      iconKnobTab,
    ) : null,
  };

  return CivicButton({ ...generalKnobs, ...iconKnobs });
};
