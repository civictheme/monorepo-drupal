// phpcs:ignoreFile
import { dateIsValid, demoImage, knobBoolean, knobNumber, knobRadios, knobText, randomSentence, randomTags, randomUrl, shouldRender, slotKnobs } from '../../00-base/storybook/storybook.utils';

import CivicThemePromoCard from './promo-card.twig';

export default {
  title: 'Molecules/List/Promo Card',
  parameters: {
    layout: 'centered',
    storyLayoutSize: 'small',
  },
};

export const PromoCard = (parentKnobs = {}) => {
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
    subtitle: knobText('Subtitle', randomSentence(3), parentKnobs.subtitle, parentKnobs.knobTab),
    date: knobText('Date', '20 Jan 2023 11:00', parentKnobs.date, parentKnobs.knobTab),
    date_end: knobText('End date', '21 Jan 2023 15:00', parentKnobs.date_end, parentKnobs.knobTab),
    title: knobText('Title', 'Promo card name which runs across two or three lines', parentKnobs.title, parentKnobs.knobTab),
    summary: knobText('Summary', randomSentence(), parentKnobs.summary, parentKnobs.knobTab),
    link: {
      url: knobText('Link URL', randomUrl(), parentKnobs.link_url, parentKnobs.knobTab),
      is_external: knobBoolean('Link is external', false, parentKnobs.link_is_external, parentKnobs.knobTab),
      is_new_window: knobBoolean('Open in a new window', false, parentKnobs.link_is_new_window, parentKnobs.knobTab),
    },
    image: knobBoolean('With image', true, parentKnobs.with_image, parentKnobs.knobTab) ? {
      url: demoImage(),
      alt: 'Image alt text',
    } : false,
    tags: randomTags(knobNumber(
      'Number of tags',
      2,
      {
        range: true,
        min: 0,
        max: 10,
        step: 1,
      },
      parentKnobs.number_of_tags,
      parentKnobs.knobTab,
    ), true),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  knobs.date_iso = dateIsValid(knobs.date) ? new Date(knobs.date).toISOString() : null;
  knobs.date_end_iso = dateIsValid(knobs.date_end) ? new Date(knobs.date_end).toISOString() : null;

  return shouldRender(parentKnobs) ? CivicThemePromoCard({
    ...knobs,
    ...slotKnobs([
      'image_over',
      'content_top',
      'content_middle',
      'content_bottom',
    ]),
  }) : knobs;
};
