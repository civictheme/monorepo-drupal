// phpcs:ignoreFile
import CivicThemeLabel from './label.twig';
import { knobBoolean, knobRadios, knobText, shouldRender } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Atoms/Form Controls/Label',
  parameters: {
    layout: 'centered',
    knobs: {
      escapeHTML: false,
    },
  },
};

export const Label = (parentKnobs = {}) => {
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
    size: knobRadios(
      'Size',
      {
        'Extra Large': 'extra-large',
        Large: 'large',
        Regular: 'regular',
        Small: 'small',
        'Extra Small': 'extra-small',
        None: '',
      },
      'regular',
      parentKnobs.size,
      parentKnobs.knobTab,
    ),
    content: knobText('Content', 'Label content', parentKnobs.content, parentKnobs.knobTab),
    for: knobText('For', '', parentKnobs.for, parentKnobs.knobTab),
    is_required: knobBoolean('Required', false, parentKnobs.is_required, parentKnobs.knobTab),
    allow_html: knobBoolean('Allow HTML in content', false, parentKnobs.allow_html, parentKnobs.knobTab),
    modifier_class: knobText('Additional classes', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  return shouldRender(parentKnobs) ? CivicThemeLabel(knobs) : knobs;
};
