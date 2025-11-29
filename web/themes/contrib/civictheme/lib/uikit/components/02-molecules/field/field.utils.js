// phpcs:ignoreFile
import { KnobValues, randomArrayItem } from '../../00-base/storybook/storybook.utils';
import { Field } from './field.stories';

export const randomFields = (count, theme, rand, defaultInputType) => {
  rand = rand || false;

  const inputTypes = [
    'textfield',
    'textarea',
    'select',
    'radio',
    'checkbox',
  ];

  const fields = [];
  for (let i = 0; i < count; i++) {
    const inputType = defaultInputType || randomArrayItem(inputTypes);
    fields.push(Field(new KnobValues({
      theme,
      type: inputType,
      description: null,
      message: null,
      orientation: 'vertical',
    })));
  }

  return fields;
};
