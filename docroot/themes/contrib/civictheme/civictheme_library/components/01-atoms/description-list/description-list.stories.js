// phpcs:ignoreFile
import {
  boolean, number, radios, text,
} from '@storybook/addon-knobs';
import CivicThemeDescriptionList from './description-list.twig';

export default {
  title: 'Atoms/Description List',
  parameters: {
    layout: 'centered',
  },
};

export const DescriptionList = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const generateItems = (count) => {
    const items = [];
    for (let i = 0; i < count; i++) {
      items.push({
        title: `Term ${i + 1}`,
        description: `Description for term ${i + 1}`,
      });
    }
    return items;
  };

  const generalKnobs = {
    theme: radios(
      'Theme', {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      generalKnobTab,
    ),
    items: generateItems(number(
      'Number of items',
      5,
      {
        range: true,
        min: 0,
        max: 10,
        step: 1,
      },
      generalKnobTab,
    )),
    is_inline: boolean('Inline', true, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  return CivicThemeDescriptionList({
    ...generalKnobs,
  });
};
