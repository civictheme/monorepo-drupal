import {
  boolean,
  radios,
  text,
} from '@storybook/addon-knobs';
import imageFile from '../../../assets/image.png';
import { getSlots } from '../../00-base/base.stories';
import CivicSubjectCard from './subject-card.twig';
import './subject-card.scss';

export default {
  title: 'Molecule/Card',
  parameters: {
    layout: 'centered',
  },
};

export const SubjectCardStories = () => {
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
  
  return CivicSubjectCard({
    ...generalKnobs,
    ...getSlots([
      'image_over',
    ]),
  });
};
