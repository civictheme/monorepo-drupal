import {
  boolean, radios, text,
} from '@storybook/addon-knobs';
import CivicTableOfContentsStories from './table-of-contents.stories.twig';

export default {
  title: 'Organisms/Navigation',
};

export const TableOfContents = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

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
    title: text('Title', 'On this page', generalKnobTab),
    links: boolean('Set manual / automatic links', false, generalKnobTab) ? [
      {
        title: 'Introduction',
        url: '#introduction',
      },
      {
        title: 'Section 1',
        url: '#section-1',
      },
      {
        title: 'Section 2',
        url: '#section-2',
      },
      {
        title: 'Section 3',
        url: '#section-3',
      },
      {
        title: 'Section 4',
        url: '#section-4',
      },
      {
        title: 'Section 5',
        url: '#section-5',
      },
    ] : null,
    anchor_selector: text('Anchor Selector', 'h3', generalKnobTab),
    anchor_scope_selector: text('Anchor Scope Selector', '.civic-basic-content', generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  return CivicTableOfContentsStories({
    ...generalKnobs,
  });
};
