// phpcs:ignoreFile
import {
  boolean, radios, select, text,
} from '@storybook/addon-knobs';

import CivicThemeButton from './button.twig';
import './button';

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
    text: text(
      'Text',
      'Button text',
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
        'Extra Large': 'extra-large',
        Large: 'large',
        Regular: 'regular',
        Small: 'small',
        'Extra Small': 'extra-small',
        None: '',
      },
      'regular',
      generalKnobTab,
    ),
    kind: radios(
      'Kind', {
        Button: 'button',
        Link: 'link',
        Reset: 'reset',
        Submit: 'submit',
      },
      'button',
      generalKnobTab,
    ),
  };

  if (generalKnobs.kind === 'link') {
    generalKnobs.url = text('URL', 'http://example.com', generalKnobTab);
    generalKnobs.is_new_window = boolean('Open in a new window', false, generalKnobTab);
  }

  generalKnobs.is_disabled = boolean('Disabled', false, generalKnobTab);
  generalKnobs.is_external = boolean('Is external', false, generalKnobTab);
  generalKnobs.is_raw_text = boolean('Allow HTML in text', false, generalKnobTab);
  generalKnobs.attributes = text('Additional attributes', '', generalKnobTab);
  generalKnobs.modifier_class = text('Additional class', '', generalKnobTab);

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
    icon_size: withIcon ? radios(
      'Icon Size', {
        Regular: 'regular',
        Small: 'small',
        'Extra Small': 'extra-small',
        None: '',
      },
      'regular',
      iconKnobTab,
    ) : null,
  };

  return CivicThemeButton({ ...generalKnobs, ...iconKnobs });
};
