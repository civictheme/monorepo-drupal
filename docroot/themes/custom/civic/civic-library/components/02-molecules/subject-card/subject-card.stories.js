import {
  boolean,
  radios,
  text,
} from '@storybook/addon-knobs';
import imageFile from '../../../assets/image.png';
import { getSlots } from '../../00-base/base.stories';
import CivicSubjectCard from './subject-card.twig';

export default {
  title: 'Molecules/Card/Subject Card',
  parameters: {
    layout: 'centered',
  },
};

export const SubjectCard = () => {
  const generalKnobTab = 'General';

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
    url: text('Link URL', 'http://example.com', generalKnobTab),
    image: boolean('With image', true, generalKnobTab) ? {
      src: imageFile,
      alt: 'Image alt text',
    } : false,
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  const html = CivicSubjectCard({
    ...generalKnobs,
    ...getSlots([
      'image_over',
    ]),
  });

  return `<div class="story-wrapper-size--small">${html}</div>`;
};
