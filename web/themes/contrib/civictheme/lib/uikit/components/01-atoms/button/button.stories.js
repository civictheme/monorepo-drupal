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
      'Theme',
      {
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
      'Type',
      {
        Primary: 'primary',
        Secondary: 'secondary',
        Tertiary: 'tertiary',
        None: '',
      },
      'primary',
      generalKnobTab,
    ),
    size: radios(
      'Size',
      {
        Large: 'large',
        Regular: 'regular',
        Small: 'small',
        None: '',
      },
      'regular',
      generalKnobTab,
    ),
    kind: radios(
      'Kind',
      {
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
  generalKnobs.modifier_class = text('Additional class', '', generalKnobTab);
  generalKnobs.attributes = text('Additional attributes', '', generalKnobTab);

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
      'after',
      iconKnobTab,
    ) : null,
  };

  return CivicThemeButton({ ...generalKnobs, ...iconKnobs });
};
