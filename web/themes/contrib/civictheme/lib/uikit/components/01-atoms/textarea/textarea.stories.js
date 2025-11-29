// phpcs:ignoreFile
import CivicThemeTextarea from './textarea.twig';
import { knobBoolean, knobNumber, knobRadios, knobText, randomId, randomName, shouldRender } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Atoms/Form Controls/Textarea',
  parameters: {
    layout: 'centered',
    storyLayoutSize: 'small',
  },
};

export const Textarea = (parentKnobs = {}) => {
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
    rows: knobNumber(
      'Number of rows',
      5,
      {
        range: true,
        min: 1,
        max: 10,
        step: 1,
      },
      parentKnobs.rows,
      parentKnobs.knobTab,
    ),
    placeholder: knobText('Placeholder', 'Placeholder', parentKnobs.placeholder, parentKnobs.knobTab),
    value: knobText('Value', '', parentKnobs.value, parentKnobs.knobTab),
    name: randomName(),
    id: randomId(),
    is_required: knobBoolean('Required', false, parentKnobs.is_required, parentKnobs.knobTab),
    is_invalid: knobBoolean('Has error', false, parentKnobs.is_invalid, parentKnobs.knobTab),
    is_disabled: knobBoolean('Disabled', false, parentKnobs.is_disabled, parentKnobs.knobTab),
    modifier_class: knobText('Additional classes', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  return shouldRender(parentKnobs) ? CivicThemeTextarea(knobs) : knobs;
};
