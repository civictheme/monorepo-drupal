// phpcs:ignoreFile
import CivicThemeSelect from './select.twig';
import { generateSelectOptions, knobBoolean, knobRadios, knobText, randomId, randomName, shouldRender } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Atoms/Form Controls/Select',
  parameters: {
    layout: 'centered',
    storyLayoutSize: 'small',
  },
};

export const Select = (parentKnobs = {}) => {
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
    is_multiple: knobBoolean('Is multiple', false, parentKnobs.is_multiple, parentKnobs.knobTab),
    options: knobBoolean('With options', true, parentKnobs.options, parentKnobs.knobTab) ? generateSelectOptions(10, (knobBoolean('Options have groups', false, null, parentKnobs.knobTab) ? 'optgroup' : 'option')) : [],
    name: randomName(),
    id: randomId(),
    is_required: knobBoolean('Required', false, parentKnobs.is_required, parentKnobs.knobTab),
    is_invalid: knobBoolean('Has error', false, parentKnobs.is_invalid, parentKnobs.knobTab),
    is_disabled: knobBoolean('Disabled', false, parentKnobs.is_disabled, parentKnobs.knobTab),
    modifier_class: knobText('Additional classes', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  return shouldRender(parentKnobs) ? CivicThemeSelect(knobs) : knobs;
};
