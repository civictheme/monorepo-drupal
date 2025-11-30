// phpcs:ignoreFile
import CivicThemePublicationCard from './publication-card.twig';
import { convertDate, demoImage, knobBoolean, knobRadios, knobText, randomSentence, randomUrl, shouldRender, slotKnobs } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Molecules/List/Publication Card',
  parameters: {
    layout: 'centered',
    storyLayoutSize: 'medium',
  },
};

export const PublicationCard = (parentKnobs = {}) => {
  const date = convertDate(null);

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
    title: knobText('Title', 'Publication or whitepaper main title', parentKnobs.title, parentKnobs.knobTab),
    summary: knobText('Summary', randomSentence(), parentKnobs.summary, parentKnobs.knobTab),
    image: knobBoolean('With image', true, parentKnobs.with_image, parentKnobs.knobTab) ? {
      url: demoImage(),
      alt: 'Image alt text',
    } : false,
    file: knobBoolean('With file', true, parentKnobs.with_file, parentKnobs.knobTab) ? {
      url: randomUrl(),
      name: 'Document.doc',
      ext: 'doc',
      size: '42.88 KB',
      created: date,
      changed: date,
      icon: 'word-file',
    } : null,
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  return shouldRender(parentKnobs) ? CivicThemePublicationCard({
    ...knobs,
    ...slotKnobs([
      'image_over',
      'content_top',
      'content_middle',
      'content_bottom',
    ]),
  }) : knobs;
};
