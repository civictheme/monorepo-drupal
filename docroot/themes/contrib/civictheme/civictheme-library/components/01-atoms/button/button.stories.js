import {
  boolean, radios, select, text,
} from '@storybook/addon-knobs';

import CivicButton from './button.twig';
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
    type: radios(
      'Type', {
        Primary: 'primary',
        Secondary: 'secondary',
        Tertiary: 'tertiary',
        Chip: 'chip',
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
        Checkbox: 'checkbox',
        Radio: 'radio',
        None: '',
      },
      'button',
      generalKnobTab,
    ),
  };

  if (generalKnobs.kind === 'link') {
    generalKnobs.url = text('URL', 'http://example.com', generalKnobTab);
    generalKnobs.is_new_window = boolean('Open in a new window', false, generalKnobTab);
  }

  generalKnobs.text = text('Text', 'Button text', generalKnobTab);
  generalKnobs.title = text('Title', 'Button Title', generalKnobTab);
  generalKnobs.id = text('ID attribute', 'id123', generalKnobTab);
  generalKnobs.name = text('Name attribute', 'mybutton', generalKnobTab);
  generalKnobs.is_raw_text = boolean('Allow HTML in text', false, generalKnobTab);
  generalKnobs.is_external = boolean('Is external', false, generalKnobTab);
  generalKnobs.is_disabled = boolean('Disabled', false, generalKnobTab);
  generalKnobs.is_dismissible = boolean('Dismissible', false, generalKnobTab);
  generalKnobs.is_selected = boolean('Selected', false, generalKnobTab);
  generalKnobs.modifier_class = text('Additional class', '', generalKnobTab);
  generalKnobs.attributes = text('Additional attributes', '', generalKnobTab);

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
        'Extra Large': 'extra-large',
        Large: 'large',
        Regular: 'regular',
        Small: 'small',
        'Extra Small': 'extra-small',
        None: '',
      },
      'regular',
      iconKnobTab,
    ) : null,
  };

  return CivicButton({ ...generalKnobs, ...iconKnobs });
};
