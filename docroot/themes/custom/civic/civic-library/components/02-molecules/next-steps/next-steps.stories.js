import { radios, text } from '@storybook/addon-knobs';
import CivicNextSteps from './next-steps.twig';

export default {
  title: 'Molecules/Next Steps',
};

const exampleSummary = 'Short summary explaining why this link is relevant.';

export const NextSteps = () => CivicNextSteps({
  theme: radios(
    'Theme',
    {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
  ),
  title: text('Title', 'Next step title from knob'),
  summary: text('Summary', exampleSummary),
  url: text('Link', 'http://example.com'),
  modifier_class: text('Additional class', ''),
});
