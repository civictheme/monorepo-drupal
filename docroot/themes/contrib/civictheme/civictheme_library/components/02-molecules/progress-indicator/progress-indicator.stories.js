import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicThemeProgressIndicator from './progress-indicator.twig';

export default {
  title: 'Molecules/Progress Indicator',
  component: CivicThemeProgressIndicator,
};

export const ProgressIndicator = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';
  const stepsKnobTab = 'Steps';

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
    steps: boolean('With steps', true, generalKnobTab) ? [
      {
        title: text('Title for step 1', 'Step title 1', stepsKnobTab),
        status: 'todo',
      },
      {
        title: text('Title for step 2', 'Step title 2', stepsKnobTab),
        status: 'doing',
      },
      {
        title: text('Title for step 3', 'Step title 3', stepsKnobTab),
        status: 'done',
      },
      {
        title: text('Title for step 4', 'Step without a status', stepsKnobTab),
      },
    ] : null,
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  return CivicThemeProgressIndicator({
    ...generalKnobs,
  });
};
