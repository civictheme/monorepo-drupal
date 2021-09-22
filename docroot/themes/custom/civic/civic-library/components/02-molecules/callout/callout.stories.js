import {radios, text} from '@storybook/addon-knobs'
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
    'Callout',
  ),
  title: text('Title', 'Callout title from knob', 'Callout'),
  summary: text('Summary', exampleSummary, 'Callout'),
  links: [
    {
      text: text('Text', 'CTA 1', 'CTA 1'),
      url: text('URL', '', 'CTA 1'),
      type: radios(
        'Type',
        {
          'None': 'none',
          'Primary': 'primary',
          'Primary Accent': 'primary-accent',
          'Secondary': 'secondary',
          'Secondary Accent': 'secondary-accent',
        },
        'primary',
        'CTA 1',
      ),
      size: radios(
        'Size',
        {
          'Large': 'large',
          'Regular': 'regular',
          'Small': 'small',
        },
        'regular',
        'CTA 1',
      ),
    },
    {
      text: text('Text', 'CTA 2', 'CTA 2'),
      url: text('URL', '', 'CTA 2'),
      type: radios(
        'Type',
        {
          'None': 'none',
          'Primary': 'primary',
          'Primary Accent': 'primary-accent',
          'Secondary': 'secondary',
          'Secondary Accent': 'secondary-accent',
        },
        'none',
        'CTA 2',
      ),
      size: radios(
        'Size',
        {
          'Large': 'large',
          'Regular': 'regular',
          'Small': 'small',
        },
        'regular',
        'CTA 2',
      ),
    },
  ]
});
