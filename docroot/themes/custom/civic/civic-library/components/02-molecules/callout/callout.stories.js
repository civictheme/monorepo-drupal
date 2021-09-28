import {boolean, radios, select, text} from '@storybook/addon-knobs'
import CivicCallout from "./callout.twig";

export default {
  title: 'Molecule/Callout'
}

let exampleSummary = 'Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.'


export const Callout = () => {

  const cta1 = 'CTA 1';
  const cta2 = 'CTA 2';
  const callout = 'Callout';

  const calloutParams = {
    theme: radios(
      'Theme',
      {
        'Light': 'light',
        'Dark': 'dark',
      },
      'light',
      callout,
    ),
    title: text('Title', 'Callout title from knob', callout),
    summary: text('Summary', exampleSummary, 'Callout'),
    links: [
      {
        text: text('Text', 'CTA 1', cta1),
        url: text('URL', '', cta1),
        type: radios(
          'Type',
          {
            'Primary': 'primary',
            'Secondary': 'secondary',
            'Tertiary': 'tertiary'
          },
          'primary',
          cta1,
        ),
        size: radios(
          'Size',
          {
            'Large': 'large',
            'Regular': 'regular',
            'Small': 'small',
          },
          'regular',
          cta1,
        ),
      },
      {
        text: text('Text', 'CTA 2', cta2),
        url: text('URL', '', cta2),
        type: radios(
          'Type', {
            'Primary': 'primary',
            'Secondary': 'secondary',
            'Tertiary': 'tertiary'
          },
          'secondary',
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
          cta2,
        ),
      },
    ]
  }

  return CivicCallout(calloutParams);
};
