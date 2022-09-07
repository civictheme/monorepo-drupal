// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicThemeLayout from './layout.twig';
import CivicThemeLayoutSingleColumn from './content-layout--single-column.twig';
import CivicThemeLayoutSingleColumnContained
  from './content-layout--single-column-contained.twig';
import { getSlots, randomText } from '../base.stories';

export default {
  title: 'Base/Layout',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Layout = (knobTab) => {
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
    content: boolean('Show content', true, generalKnobTab) ? `<strong>Content text</strong> ${randomText(30)}` : '',
    sidebar: boolean('Show sidebar', false, generalKnobTab) ? `<strong>Sidebar text</strong> ${randomText(20)}` : '',
    is_contained: boolean('Is contained', false, generalKnobTab),
    layout: radios(
      'Layout',
      {
        'Single Column': 'single_column',
        'Single Column Contained': 'single_column_contained',
      },
      'single_column',
      generalKnobTab,
    ),
    vertical_space: radios(
      'Vertical space',
      {
        None: 'none',
        Top: 'top',
        Bottom: 'bottom',
        Both: 'both',
      },
      'none',
      generalKnobTab,
    ),
    content_attributes: text('Content attributes', '', generalKnobTab),
    sidebar_attributes: text('Sidebar attributes', '', generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  if (generalKnobs.content) {
    switch (generalKnobs.layout) {
      case 'single_column':
        generalKnobs.content = CivicThemeLayoutSingleColumn({
          content: generalKnobs.content,
        });
        break;

      case 'single_column_contained':
        generalKnobs.content = CivicThemeLayoutSingleColumnContained({
          content: generalKnobs.content,
        });
        break;

      default:
        generalKnobs.content = '';
    }
  }

  return CivicThemeLayout({
    ...generalKnobs,
    ...getSlots([
      'content_top',
      'content_bottom',
    ]),
  });
};
