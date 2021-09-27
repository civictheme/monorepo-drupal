import {radios, text} from '@storybook/addon-knobs'
import CivicNextSteps from "./next-steps.twig";
import './next-steps.scss';

export default {
  title: 'Molecule/Next Steps'
}

let exampleSummary = 'Short summary explaining why this link is relevant.';

export const NextSteps = () => CivicNextSteps({
  theme: radios(
    'Theme',
    {
      'Light': 'light',
      'Dark': 'dark',
    },
    'light',
  ),
  title: text('Title', 'Next step title from knob'),
  summary: text('Summary', exampleSummary),
  url: text('Link', 'https://www.google.com'),
  modifier_class: text('Additional class', ''),
});
