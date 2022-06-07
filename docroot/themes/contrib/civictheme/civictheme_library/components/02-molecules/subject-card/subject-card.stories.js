import { boolean, radios, text } from '@storybook/addon-knobs';
import { demoImage, getSlots, randomUrl } from '../../00-base/base.stories';
import CivicThemeSubjectCard from './subject-card.twig';

export default {
  title: 'Molecules/Card/Subject Card',
  parameters: {
    layout: 'centered',
  },
};

export const SubjectCard = (knobTab) => {
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
    title: text('Title', 'Subject card title which runs across two or three lines', generalKnobTab),
    url: text('Link URL', randomUrl(), generalKnobTab),
    is_external: boolean('Is external', false, generalKnobTab),
    image: boolean('With image', true, generalKnobTab) ? {
      src: demoImage(),
      alt: 'Image alt text',
    } : false,
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  const html = CivicThemeSubjectCard({
    ...generalKnobs,
    ...getSlots([
      'image_over',
    ]),
  });

  return `<div class="story-wrapper-size--small">${html}</div>`;
};
