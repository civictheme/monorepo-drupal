import { radios, text } from '@storybook/addon-knobs';
import CivicCallout from './callout.twig';

export default {
  title: 'Molecule/Callout',
  parameters: {
    layout: 'centered',
  },
};

export const Callout = () => {
  const calloutKnobTab = 'Callout';
  const cta1KnobTab = 'CTA 1';
  const cta2KnobTab = 'CTA 2';

  const calloutKnobs = {
    theme: radios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      calloutKnobTab,
    ),
    title: text('Title', 'Callout title from knob', calloutKnobTab),
    summary: text('Summary', 'Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.', calloutKnobTab),
    links: [
      {
        text: text('Text', 'CTA 1', cta1KnobTab),
        url: text('URL', 'http://example.com', cta1KnobTab),
        type: radios(
          'Type',
          {
            Primary: 'primary',
            Secondary: 'secondary',
            Tertiary: 'tertiary',
          },
          'primary',
          cta1KnobTab,
        ),
        size: radios(
          'Size',
          {
            Large: 'large',
            Regular: 'regular',
            Small: 'small',
          },
          'regular',
          cta1KnobTab,
        ),
      },
      {
        text: text('Text', 'CTA 2', cta2KnobTab),
        url: text('URL', 'http://example.com', cta2KnobTab),
        type: radios(
          'Type', {
            Primary: 'primary',
            Secondary: 'secondary',
            Tertiary: 'tertiary',
          },
          'neutral',
          'CTA 2',
        ),
        size: radios(
          'Size',
          {
            Large: 'large',
            Regular: 'regular',
            Small: 'small',
          },
          'regular',
          cta2KnobTab,
        ),
      },
    ],
  };

  return CivicCallout(calloutKnobs);
};
