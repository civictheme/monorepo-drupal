import {text, select, boolean, radios} from '@storybook/addon-knobs'
import CivicCallout from "./callout.twig";

export default {
  title: 'Molecule/Callout'
}

let exampleSummary = 'Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.'

export const Callout = () => CivicCallout({
  modifier_class: [
    radios(
      'Type',
      {
        'Light': 'civic-callout--light',
        'Dark': 'civic-callout--dark',
      },
      'civic-callout--light',
    ),
  ],
  title: text('Title', 'Callout title from knob'),
  summary: text('Summary', exampleSummary),
  cta_text: text('CTA Text', 'CTA Button Text'),
  cta_type: radios(
    'CTA Type',
    {
      'Primary': 'civic-button--primary',
      'Primary Accent': 'civic-button--primary-accent',
      'Secondary': 'civic-button--secondary',
      'Secondary Accent': 'civic-button--secondary-accent',
    },
    'civic-button--primary',
  ),
  cta_url: text('CTA URL', ''),
  link_text: text('Link Text', 'Link Text from knob'),
  link_url: text('Link URL', '')
});




