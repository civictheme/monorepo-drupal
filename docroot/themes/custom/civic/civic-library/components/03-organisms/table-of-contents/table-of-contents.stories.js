import {
  radios, text, object,
} from '@storybook/addon-knobs';
import CivicTableOfContentsStories from './table-of-contents.stories.twig';

export default {
  title: 'Organisms/Table Of Contents',
  parameters: {
    layout: 'centered',
  },
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
    links: object('Links', [
      {
        text: 'Introduction',
        url: '#introduction',
        active: '',
      },
      {
        text: 'Section 1',
        url: '#section-1',
        active: '',
      },
      {
        text: 'Section 2',
        url: '#section-2',
        active: '',
      },
      {
        text: 'Section 3',
        url: '#section-3',
        active: '',
      },
      {
        text: 'Section 4',
        url: '#section-4',
        active: '',
      },
      {
        text: 'Section 5',
        url: '#section-5',
        active: '',
      },
    ], generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  return CivicTableOfContentsStories({
    ...generalKnobs,
  });
};
