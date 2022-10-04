// phpcs:ignoreFile
import {
  number, radios, text,
} from '@storybook/addon-knobs';
import CivicThemeFieldGroup from './field-group.twig';
import { Field } from '../field/field.stories';

export default {
  title: 'Molecules/Field Group',
  parameters: {
    layout: 'centered',
  },
};

export const FieldGroup = () => {
  const generalKnobTab = 'General';
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
    fields: number(
      'Number of fields',
      3,
      {
        range: true,
        min: 0,
        max: 10,
        step: 1,
      },
      generalKnobTab,
    ),
    label: text('Label', 'Field label', generalKnobTab),
    description: text('Description', 'Content sample with long text that spans on the multiple lines to test text vertical spacing', generalKnobTab),
    modifier_class: `story-wrapper-size--medium ${text('Additional class', '', generalKnobTab)}`,
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  const fields = [];
  let i = 1;
  while (i <= generalKnobs.fields) {
    fields.push(Field(`Field ${i}`, `Field ${i} Control`, true, i));
    i += 1;
  }

  const fieldKnobs = {
    fields,
  };

  return CivicThemeFieldGroup({
    ...generalKnobs,
    ...fieldKnobs,
  });
};
