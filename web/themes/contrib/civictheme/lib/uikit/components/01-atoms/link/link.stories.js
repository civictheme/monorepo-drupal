// phpcs:ignoreFile
import CivicThemeLink from './link.twig';
import { knobBoolean, knobRadios, knobSelect, knobText, randomUrl, shouldRender } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Atoms/Link',
  parameters: {
    layout: 'centered',
  },
};

export const Link = (parentKnobs = {}) => {
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
    text: knobText('Text', 'Link text', parentKnobs.text, parentKnobs.knobTab),
    title: knobText('Title', 'Link title', parentKnobs.title, parentKnobs.knobTab),
    hidden_text: knobText('Link hidden text', 'Link hidden text', parentKnobs.hidden_text, parentKnobs.knobTab),
    url: knobText('URL', randomUrl(), parentKnobs.url, parentKnobs.knobTab),
    is_external: knobBoolean('Is external', false, parentKnobs.is_external, parentKnobs.knobTab),
    is_active: knobBoolean('Is active', false, parentKnobs.is_active, parentKnobs.knobTab),
    is_disabled: knobBoolean('Is disabled', false, parentKnobs.is_disabled, parentKnobs.knobTab),
    is_new_window: knobBoolean('Open in a new window', false, parentKnobs.is_new_window, parentKnobs.knobTab),
    with_icon: knobBoolean('With icon', false, parentKnobs.with_icon, parentKnobs.knobTab),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  const iconKnobTab = 'Icon';
  const iconKnobs = knobs.with_icon ? {
    icon_placement: knobRadios(
      'Icon Position',
      {
        Before: 'before',
        After: 'after',
      },
      'before',
      parentKnobs.icon_placement,
      iconKnobTab,
    ),
    icon: knobSelect('Icon', Object.values(ICONS), Object.values(ICONS)[0], parentKnobs.icon, iconKnobTab),
  } : null;

  const combinedKnobs = { ...knobs, ...iconKnobs };

  return shouldRender(parentKnobs) ? CivicThemeLink(combinedKnobs) : combinedKnobs;
};
