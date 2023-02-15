// phpcs:ignoreFile
import {
  radios, text, number, boolean,
} from '@storybook/addon-knobs';
import { randomFormElements } from '../../00-base/base.utils';
import CivicThemeFieldset from './fieldset.twig';

export default {
  title: 'Atoms/Fieldset',
};

export const Fieldset = () => {
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
    legend: text('Legend', 'Fieldset legend', generalKnobTab),
    description: text('Description', 'Fieldset example description', generalKnobTab),
    required: boolean('Required', true, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  const numOfElements = number(
    'Number of form elements',
    1,
    {
      range: true,
      min: 0,
      max: 10,
      step: 1,
    },
    generalKnobTab,
  );

  const html = CivicThemeFieldset({
    ...generalKnobs,
    children: randomFormElements(numOfElements, theme, true).join(''),
  });

  return `<div class="container"><div class="row"><div class="col-xxs-12">${html}</div></div></div>`;
};
