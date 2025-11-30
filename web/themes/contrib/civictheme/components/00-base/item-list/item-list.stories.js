// phpcs:ignoreFile
import CivicThemeItemList from './item-list.twig';
import { generateItems, knobBoolean, knobNumber, knobRadios, knobText, placeholder, randomSentence, shouldRender } from '../storybook/storybook.utils';

export default {
  title: 'Base/Item List',
  parameters: {
    layout: 'centered',
    storyLayoutSize: 'large',
  },
};

export const ItemList = (parentKnobs = {}) => {
  const knobs = {
    direction: knobRadios(
      'Direction',
      {
        Horizontal: 'horizontal',
        Vertical: 'vertical',
      },
      'horizontal',
      parentKnobs.direction,
      parentKnobs.knobTab,
    ),
    size: knobRadios(
      'Size',
      {
        Large: 'large',
        Regular: 'regular',
        Small: 'small',
      },
      'regular',
      parentKnobs.size,
      parentKnobs.knobTab,
    ),
    no_gap: knobBoolean('No gap', false, parentKnobs.no_gap, parentKnobs.knobTab),
    items_count: knobNumber(
      'Items count',
      5,
      {
        range: true,
        min: 0,
        max: 10,
        step: 1,
      },
      parentKnobs.items_count,
      parentKnobs.knobTab,
    ),
    long_placeholder_text: knobBoolean('Long placeholder text', false, parentKnobs.long_placeholder_text, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
  };
  knobs.items = generateItems(
    knobs.items_count,
    placeholder(knobs.long_placeholder_text ? randomSentence(30) : 'Content placeholder'),
  );

  return shouldRender(parentKnobs) ? CivicThemeItemList(knobs) : knobs;
};
