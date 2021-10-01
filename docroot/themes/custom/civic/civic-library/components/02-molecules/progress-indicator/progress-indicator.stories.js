import { radios, text } from '@storybook/addon-knobs'
import CivicProgressIndicator from "./progress-indicator.twig";

/**
 * Storybook Definition.
 */
export default {
  title: 'Molecule/Progress Indicator',
  component: CivicProgressIndicator,
};

export const ProgressIndicator = () => {
  return CivicProgressIndicator({
    theme: radios(
      'Theme',
      {
        'Light': 'light',
        'Dark': 'dark',
      },
      'light',
    ),
    steps: [
      {
        title: text('Title for step 1', 'Step title 1'),
        status: 'todo',
      },
      {
        title: text('Title for step 2', 'Step title 2'),
        status: 'doing',
      },
      {
        title: text('Title for step 3', 'Step title 3'),
        status: 'done',
      },
      {
        title: text('Title for step 4', 'Step without a status'),
      },
    ],
    modifier_class: text('Additional class', ''),
  });
}
