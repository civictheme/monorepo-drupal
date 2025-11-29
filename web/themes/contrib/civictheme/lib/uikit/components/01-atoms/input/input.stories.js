// phpcs:ignoreFile
import CivicThemeinput from './input.twig';
import { knobBoolean, knobRadios, knobText, randomId, randomName, shouldRender } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Atoms/Form Controls/Input',
  parameters: {
    layout: 'centered',
    storyLayoutSize: 'small',
  },
};

export const Input = (parentKnobs = {}) => {
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
    type: knobRadios(
      'Type',
      {
        Color: 'color',
        Date: 'date',
        Email: 'email',
        File: 'file',
        Image: 'image',
        Month: 'month',
        Number: 'number',
        Password: 'password',
        Range: 'range',
        Search: 'search',
        Tel: 'tel',
        Time: 'time',
        Url: 'url',
        Week: 'week',
        Other: 'other',
      },
      'email',
      parentKnobs.type,
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

  return shouldRender(parentKnobs) ? CivicThemeinput(knobs) : knobs;
};
