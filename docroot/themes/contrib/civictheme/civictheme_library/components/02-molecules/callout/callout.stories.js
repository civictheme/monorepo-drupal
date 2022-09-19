// phpcs:ignoreFile
import { radios, text } from '@storybook/addon-knobs';
import CivicThemeCallout from './callout.twig';

export default {
  title: 'Molecules/Callout',
  parameters: {
    layout: 'centered',
  },
};

export const Callout = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';
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
      generalKnobTab,
    ),
    title: text('Title', 'Callout title from knob', generalKnobTab),
    summary: text('Summary', 'Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.', generalKnobTab),
    vertical_spacing: radios(
      'Vertical spacing',
      {
        None: 'none',
        Top: 'top',
        Bottom: 'bottom',
        Both: 'both',
      },
      'none',
      generalKnobTab,
    ),
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
          'secondary',
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
    modifier_class: `story-wrapper-size--large ${text('Additional class', '', generalKnobTab)}`,
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  const html = CivicThemeCallout(calloutKnobs);

  return `${html}`;
};
