// phpcs:ignoreFile
import { convertDate, demoImage, knobDate, knobNumber, knobRadios, knobText, randomLinks, randomSentence, randomTags, shouldRender, slotKnobs } from '../../00-base/storybook/storybook.utils';

import CivicThemeCampaign from './campaign.twig';

export default {
  title: 'Organisms/Campaign',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Campaign = (parentKnobs = {}) => {
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
    title: knobText('Title', 'Campaign heading which runs across two or three lines', parentKnobs.title, parentKnobs.knobTab),
    content: knobText('Content', randomSentence(), parentKnobs.content, parentKnobs.knobTab),
    date: knobDate('Date', new Date(), parentKnobs.date, parentKnobs.knobTab),
    image: {
      url: demoImage(),
      alt: 'Image alt text',
    },
    image_position: knobRadios(
      'Image position',
      {
        Left: 'left',
        Right: 'right',
      },
      'left',
      parentKnobs.image_position,
      parentKnobs.knobTab,
    ),
    links: randomLinks(knobNumber(
      'Number of links',
      2,
      {
        range: true,
        min: 0,
        max: 10,
        step: 1,
      },
      parentKnobs.number_of_links,
      parentKnobs.knobTab,
    ), 10),
    tags: randomTags(knobNumber(
      'Number of tags',
      1,
      {
        range: true,
        min: 0,
        max: 10,
        step: 1,
      },
      parentKnobs.number_of_tags,
      parentKnobs.knobTab,
    ), true),
    vertical_spacing: knobRadios(
      'Vertical spacing',
      {
        None: 'none',
        Top: 'top',
        Bottom: 'bottom',
        Both: 'both',
      },
      'both',
      parentKnobs.vertical_spacing,
      parentKnobs.knobTab,
    ),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  knobs.date = convertDate(knobs.date);

  return shouldRender(parentKnobs) ? CivicThemeCampaign({
    ...knobs,
    ...slotKnobs([
      'content_top',
      'content_middle',
      'content_bottom',
    ]),
  }) : knobs;
};
