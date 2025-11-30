// phpcs:ignoreFile
import { demoImage, knobBoolean, knobRadios, knobText, randomUrl, shouldRender, slotKnobs } from '../../00-base/storybook/storybook.utils';
import CivicThemeSubjectCard from './subject-card.twig';

export default {
  title: 'Molecules/List/Subject Card',
  parameters: {
    layout: 'centered',
    storyLayoutSize: 'small',
  },
};

export const SubjectCard = (parentKnobs = {}) => {
  const knobs = {
    theme: knobRadios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      parentKnobs.theme,
      parentKnobs.knobTab,
    ),
    title: knobText('Title', 'Subject card title which runs across two or three lines', parentKnobs.title, parentKnobs.knobTab),
    link: {
      url: knobText('Link URL', randomUrl(), parentKnobs.link_url, parentKnobs.knobTab),
      is_external: knobBoolean('Link is external', false, parentKnobs.link_is_external, parentKnobs.knobTab),
      is_new_window: knobBoolean('Open in a new window', false, parentKnobs.link_is_new_window, parentKnobs.knobTab),
    },
    image: knobBoolean('With image', true, parentKnobs.with_image, parentKnobs.knobTab) ? {
      url: demoImage(),
      alt: 'Image alt text',
    } : false,
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  return shouldRender(parentKnobs) ? CivicThemeSubjectCard({
    ...knobs,
    ...slotKnobs([
      'image_over',
    ]),
  }) : knobs;
};
