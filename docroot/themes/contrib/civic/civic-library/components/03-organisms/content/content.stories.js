import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicContent from './content.twig';
import CivicLayoutSingleColumn from './content-layout--single-column.twig';
import CivicLayoutSingleColumnContained
  from './content-layout--single-column-contained.twig';
import { getSlots, randomText } from '../../00-base/base.stories';

export default {
  title: 'Organisms/Content',
};

export const Content = (knobTab) => {
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
    sidebar: boolean('Show sidebar', false, generalKnobTab) ? `<strong>Sidebar text</strong> ${randomText(20)}` : false,
    content_attributes: text('Content attributes', '', generalKnobTab),
    sidebar_attributes: text('Sidebar attributes', '', generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };
  let content = boolean('Show content', true, generalKnobTab) ? `<strong>Content text</strong> ${randomText(30)}` : '';

  const layout = radios('Layout', [
    'Single Column',
    'Single Column Contained',
  ], 'Single Column', generalKnobTab);

  if (content) {
    switch (layout) {
      case 'Single Column':
        content = CivicLayoutSingleColumn({
          content,
        });
        break;
      case 'Single Column Contained':
        content = CivicLayoutSingleColumnContained({
          content,
        });
        break;
      default:
        content = '';
    }
  }

  return CivicContent({
    ...generalKnobs,
    ...getSlots([
      'content_top',
      'content_bottom',
    ]),
    content,
  });
};
