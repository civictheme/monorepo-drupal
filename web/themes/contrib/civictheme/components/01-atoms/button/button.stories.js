// phpcs:ignoreFile
import CivicThemeButton from './button.twig';
import './button';
import { knobBoolean, knobRadios, knobSelect, knobText, shouldRender } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Atoms/Form Controls/Button',
  parameters: {
    layout: 'centered',
  },
};

export const Button = (parentKnobs = {}) => {
  const knobs = {
    theme: knobRadios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      parentKnobs.theme,
      parentKnobs.knobTab,
    ),
    text: knobText(
      'Text',
      'Button text',
      parentKnobs.text,
      parentKnobs.knobTab,
    ),
    type: knobRadios(
      'Type',
      {
        Primary: 'primary',
        Secondary: 'secondary',
        Tertiary: 'tertiary',
        None: '',
      },
      'primary',
      parentKnobs.type,
      parentKnobs.knobTab,
    ),
    size: knobRadios(
      'Size',
      {
        Large: 'large',
        Regular: 'regular',
        Small: 'small',
        None: '',
      },
      'regular',
      parentKnobs.size,
      parentKnobs.knobTab,
    ),
    kind: knobRadios(
      'Kind',
      {
        Button: 'button',
        Link: 'link',
        Reset: 'reset',
        Submit: 'submit',
      },
      'button',
      parentKnobs.kind,
      parentKnobs.knobTab,
    ),
  };

  if (knobs.kind === 'link') {
    knobs.url = knobText('URL', 'http://example.com', parentKnobs.url, parentKnobs.knobTab);
    knobs.is_new_window = knobBoolean('Open in a new window', false, parentKnobs.is_new_window, parentKnobs.knobTab);
  }

  knobs.is_disabled = knobBoolean('Disabled', false, parentKnobs.is_disabled, parentKnobs.knobTab);
  knobs.is_external = knobBoolean('Is external', false, parentKnobs.is_external, parentKnobs.knobTab);
  knobs.allow_html = knobBoolean('Allow HTML in text', false, parentKnobs.allow_html, parentKnobs.knobTab);
  knobs.modifier_class = knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab);
  knobs.attributes = knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab);

  const withIcon = knobBoolean('With icon', false, parentKnobs.with_icon, parentKnobs.knobTab);

  const iconKnobTab = 'Icon';
  const iconKnobs = {
    icon: withIcon ? knobSelect('Icon', Object.values(ICONS), Object.values(ICONS)[0], parentKnobs.icon, iconKnobTab) : null,
    icon_placement: withIcon ? knobRadios(
      'Position',
      {
        Before: 'before',
        After: 'after',
      },
      'after',
      parentKnobs.icon_position,
      iconKnobTab,
    ) : null,
  };

  const combinedKnobs = { ...knobs, ...iconKnobs };

  return shouldRender(parentKnobs) ? CivicThemeButton(combinedKnobs) : combinedKnobs;
};
