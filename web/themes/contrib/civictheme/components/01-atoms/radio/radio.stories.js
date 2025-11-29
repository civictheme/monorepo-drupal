// phpcs:ignoreFile
import CivicThemeRadio from './radio.twig';
import { knobBoolean, knobRadios, knobText, randomId, randomInt, randomName, shouldRender } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Atoms/Form Controls/Radio',
  parameters: {
    layout: 'centered',
  },
};

export const Radio = (parentKnobs = {}) => {
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
    label: knobText('Label', 'Radio label', parentKnobs.label, parentKnobs.knobTab),
    name: randomName(),
    id: randomId(),
    value: randomInt(1, 1000),
    is_checked: knobBoolean('Checked', false, parentKnobs.is_checked, parentKnobs.knobTab),
    is_required: knobBoolean('Required', false, parentKnobs.is_required, parentKnobs.knobTab),
    is_invalid: knobBoolean('Has error', false, parentKnobs.is_invalid, parentKnobs.knobTab),
    is_disabled: knobBoolean('Disabled', false, parentKnobs.is_disabled, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
    modifier_class: knobText('Additional classes', '', parentKnobs.modifier_class, parentKnobs.knobTab),
  };

  return shouldRender(parentKnobs) ? CivicThemeRadio(knobs) : knobs;
};
