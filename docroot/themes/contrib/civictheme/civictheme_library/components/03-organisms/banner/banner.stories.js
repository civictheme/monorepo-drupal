// phpcs:ignoreFile
import {
  boolean, radios, select, text,
} from '@storybook/addon-knobs';
import { demoImage, getSlots } from '../../00-base/base.stories';
import CivicThemeBannerExample from './banner.stories.twig';

export default {
  title: 'Organisms/Banner',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Banner = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const theme = radios(
    'Theme',
    {
      Light: 'light',
      Dark: 'dark',
    },
    'dark',
    generalKnobTab,
  );

  const generalKnobs = {
    theme,
    title: text('Title', 'Providing visually engaging digital experiences', generalKnobTab),
    background_image: BACKGROUNDS[select('Background', Object.keys(BACKGROUNDS), Object.keys(BACKGROUNDS)[0], generalKnobTab)],
    featured_image: boolean('With featured image', true, generalKnobTab) ? {
      src: demoImage(),
      alt: 'Featured image alt text',
    } : null,
    is_decorative: boolean('Decorative', true, generalKnobTab),
    show_breadcrumb: boolean('Show breadcrumb', true, generalKnobTab),
    show_content_text: boolean('Show content text', true, generalKnobTab),
    show_content_search: boolean('Show content search', false, generalKnobTab),
    show_content_below: boolean('Show content below', false, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  return CivicThemeBannerExample({
    ...generalKnobs,
    ...getSlots([
      'content_top1',
      'content_top2',
      'content_top3',
      'content_middle',
      'content_bottom',
    ]),
  });
};
