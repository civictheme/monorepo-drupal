import {text, radios} from '@storybook/addon-knobs'
import CivicCallout from "./callout.twig";

export default {
  title: 'Molecule/Callout'
}

let exampleSummary = 'Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.'

export const Callout = () => CivicCallout({
  theme: radios(
    'Theme',
    {
      'Light': 'light',
      'Dark': 'dark',
    },
    'light',
    'Callout'
  ),
  title: text('Title', 'Callout title from knob', 'Callout'),
  summary: text('Summary', exampleSummary, 'Callout'),
  links: [
    {text: text('Text', 'Primary CTA', 'Primary CTA'), url: text('URL', '', 'Primary CTA'), type: 'civic-button--primary'},
    {text: text('Text', 'Secondary CTA', 'Secondary CTA'), url: text('URL', '', 'Secondary CTA'), type: 'civic-button--secondary'}
  ]
});




