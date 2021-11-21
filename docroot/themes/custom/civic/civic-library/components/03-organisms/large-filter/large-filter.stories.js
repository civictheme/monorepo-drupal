import { radios, number } from '@storybook/addon-knobs';

import { dropDownFilter } from '../../00-base/base.stories';

import CivicLargeFilter from './large-filter.twig';

export default {
  title: 'Organisms/Form',
};

export const LargeFilter = () => {
  const generalKnobTab = 'General';
  const theme = radios(
    'Theme',
    {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
    generalKnobTab,
  );

  const generalKnobs = {
    theme,
  };

  const filterNumber = number(
    'Number of filters',
    4,
    {
      range: true,
      min: 0,
      max: 5,
      step: 1,
    },
    generalKnobTab,
  );

  const filters = [];
  for (let i = 1; i <= filterNumber; i++) {
    const inputType = Math.round(Math.random()) ? 'radio' : 'checkbox';
    filters.push(dropDownFilter(inputType, 4, theme, true, i));
  }

  const html = CivicLargeFilter({
    ...generalKnobs,
    filters: filters.join(''),
  });

  return `<div class="container"><div class="row"><div class="col-xs-12">${html}</div></div></div>`;
};
