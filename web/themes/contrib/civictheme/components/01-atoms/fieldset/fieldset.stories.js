// phpcs:ignoreFile
import { knobBoolean, knobNumber, knobRadios, knobText, shouldRender } from '../../00-base/storybook/storybook.utils';
import CivicThemeFieldset from './fieldset.twig';
import { randomFields } from '../../02-molecules/field/field.utils';

export default {
  title: 'Atoms/Form Controls/Fieldset',
  parameters: {
    layout: 'centered',
    storyLayoutSize: 'medium',
  },
};

export const Fieldset = (parentKnobs = {}) => {
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
    legend: knobText('Legend', 'Fieldset legend', parentKnobs.legend, parentKnobs.knobTab),
    description: knobText('Description', 'Fieldset example description', parentKnobs.description, parentKnobs.knobTab),
    description_display: knobRadios(
      'Description display',
      {
        Before: 'before',
        After: 'after',
        Invisible: 'invisible',
      },
      'before',
      parentKnobs.description_display,
      parentKnobs.knobTab,
    ),
    message: knobText('Message', 'Example message', parentKnobs.message, parentKnobs.knobTab),
    message_type: knobRadios(
      'Type',
      {
        Error: 'error',
        Information: 'information',
        Warning: 'warning',
        Success: 'success',
      },
      'error',
      parentKnobs.message_type,
      parentKnobs.knobTab,
    ),
    prefix: knobText('Prefix', '', parentKnobs.prefix, parentKnobs.knobTab),
    suffix: knobText('Suffix', '', parentKnobs.suffix, parentKnobs.knobTab),
    is_required: knobBoolean('Required', true, parentKnobs.is_required, parentKnobs.knobTab),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  const numberFields = knobNumber(
    'Number of fields',
    1,
    {
      range: true,
      min: 0,
      max: 10,
      step: 1,
    },
    parentKnobs.number_fields,
    parentKnobs.knobTab,
  );

  const numberNestedFieldsets = knobNumber(
    'Number of nested fieldsets',
    1,
    {
      range: true,
      min: 0,
      max: 10,
      step: 1,
    },
    parentKnobs.number_nested_fieldsets,
    parentKnobs.knobTab,
  );

  let nested = '';
  for (let i = 0; i < numberNestedFieldsets; i++) {
    nested = CivicThemeFieldset({
      theme: knobs.theme,
      legend: `Nested fieldset ${i + 1}. ${knobs.legend}`,
      description: `Nested fieldset ${i + 1} description`,
      fields: randomFields(numberFields, knobs.theme, true).join('') + nested,
    });
  }

  const combinedKnobs = {
    ...knobs,
    fields: randomFields(numberFields, knobs.theme, true).join('') + nested,
  };

  return shouldRender(parentKnobs) ? CivicThemeFieldset(combinedKnobs) : combinedKnobs;
};
