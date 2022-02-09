import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicContent from './content.twig';
import CivicLayout from './content-layout-single-column.twig';
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
  const contained = boolean('Contained (implied when sidebar is present)', false, generalKnobTab);
  let content = boolean('Show content', true, generalKnobTab) ? `<strong>Content text</strong> ${randomText(30)}` : false;
  if (content) {
    content = CivicLayout({
      content,
      modifier_class: contained ? 'col-m-12' : '',
      contained,
    });
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
