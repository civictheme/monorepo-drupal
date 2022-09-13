// phpcs:ignoreFile
import { radios, number, text } from '@storybook/addon-knobs';
import CivicThemeCheckboxGroup from './checkbox-group.twig';
import { randomInputItems } from '../../00-base/base.stories';

export default {
  title: 'Atoms/Form/Checkbox Group',
  parameters: {
    layout: 'centered',
  },
};

export const CheckboxGroup = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

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
    direction: radios(
      'Direction',
      {
        Horizontal: 'horizontal',
        Vertical: 'vertical',
      },
      'vertical',
      generalKnobTab,
    ),
    items: randomInputItems(number(
      'Items count',
      5,
      {
        range: true,
        min: 0,
        max: 10,
        step: 1,
      },
      generalKnobTab,
    )),
    attributes: text('Additional attributes', '', generalKnobTab),
    modifier_class: text('Additional classes', '', generalKnobTab),
  };

  return CivicThemeCheckboxGroup({
    ...generalKnobs,
  });
};
