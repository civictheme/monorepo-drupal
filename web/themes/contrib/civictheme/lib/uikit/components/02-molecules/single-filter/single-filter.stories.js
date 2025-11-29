// phpcs:ignoreFile
import CivicThemeSingleFilter from './single-filter.twig';
import './single-filter';
import { knobBoolean, knobNumber, knobRadios, knobText, randomName, randomString, shouldRender, slotKnobs } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Molecules/List/Single Filter',
  parameters: {
    layout: 'fullscreen',
  },
};

export const SingleFilter = (parentKnobs = {}) => {
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
    title: knobText('Title', 'Filter results by:', parentKnobs.title, parentKnobs.knobTab),
    submit_text: knobText('Submit button text', 'Apply', parentKnobs.submit_text, parentKnobs.knobTab),
    reset_text: knobText('Reset button text', 'Clear all', parentKnobs.reset_text, parentKnobs.knobTab),
    is_multiple: knobBoolean('Multiple', false, parentKnobs.is_multiple, parentKnobs.knobTab),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  const count = knobNumber(
    'Number of filters',
    3,
    {
      range: true,
      min: 0,
      max: 15,
      step: 1,
    },
    parentKnobs.number_of_filters,
    parentKnobs.knobTab,
  );

  const selected = knobNumber(
    'Selected',
    0,
    {
      range: true,
      min: 0,
      max: count,
      step: 1,
    },
    parentKnobs.selected,
    parentKnobs.knobTab,
  );

  const items = [];
  const name = randomName(5);
  for (let i = 0; i < count; i++) {
    items.push({
      text: `Filter ${i + 1}${randomString(3)}`,
      name: knobs.is_multiple ? name + (i + 1) : name,
      is_selected: knobs.is_multiple ? (i + 1) <= selected : (i + 1) === selected,
      attributes: `id="${name}_${randomName(3)}_${i + 1}"`,
    });
  }

  knobs.items = items;

  return shouldRender(parentKnobs) ? CivicThemeSingleFilter({
    ...knobs,
    ...slotKnobs([
      'content_top',
      'content_bottom',
    ]),
  }) : knobs;
};
