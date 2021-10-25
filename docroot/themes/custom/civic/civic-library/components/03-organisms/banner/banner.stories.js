import {
  boolean, radios, select, text,
} from '@storybook/addon-knobs';
import { getSlots } from '../../00-base/base.stories';
import CivicBanner from './banner.twig';

import CivicButton from '../../01-atoms/button/button.twig';
import CivicSearch from '../../02-molecules/search/search.twig';
import CivicBreadcrumb from '../breadcrumb/breadcrumb.twig';
import CivicCard from '../../02-molecules/navigation-card/navigation-card.twig';
import CivicCardContainer from '../card-container/card-container.twig';

import imageFile from '../../../assets/image.png';

export default {
  title: 'Organisms/Banner',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Banner = () => {
  const generalKnobTab = 'General';

  const theme = radios(
    'Theme',
    {
      Light: 'light',
      Dark: 'dark',
    },
    'dark',
    generalKnobTab,
  );

  const showBreadcrumbs = boolean('Show breadcrumbs', true, generalKnobTab);
  const showContentText = boolean('Show content text', true, generalKnobTab);
  const showContentSearch = boolean('Show content search', false, generalKnobTab);
  const showContentBelow = boolean('Show content below', false, generalKnobTab);

  let contentHtml = ``;

  if (showContentText) {
    contentHtml += CivicButton({
      theme,
      text: 'Learn about our mission',
      type: 'primary',
    });
  }

  if (showContentSearch) {
    contentHtml += CivicSearch({
      theme,
      placeholder: 'Enter keywords or phrase',
      button_text: 'Search',
      description: 'Search by keyword',
    });
  }

  let contentBelowHtml = ``;

  if (showContentBelow) {
    const card = CivicCard({
      theme: 'dark',
      title: 'Register for a workshop',
      summary: 'Optional summary in the breakdown of tasks.',
      icon: 'education_mortarboard',
    });
    const container = CivicCardContainer({
      column_count: 4,
      cards: [card, card, card, card],
    });
    contentBelowHtml = `<div class="civic-banner__content__example">${container}</div>`;
  }

  const generalKnobs = {
    theme,
    title: text('Title', 'Providing visually engaging digital experiences', generalKnobTab),
    background_image: BACKGROUNDS[theme][select('Background', Object.keys(BACKGROUNDS[theme]), Object.keys(BACKGROUNDS[theme])[0], generalKnobTab)],
    featured_image: boolean('With featured image', true, generalKnobTab) ? {
      src: imageFile,
      alt: 'Featured image alt',
    } : null,
    decorative: boolean('Decorative', true, generalKnobTab),
    breadcrumbs: showBreadcrumbs ? CivicBreadcrumb({
      theme,
      links: [
        { text: 'Home', url: 'http://example.com' },
        { text: 'Sub Page', url: 'http://example.com' },
        { text: 'Active Page', url: 'http://example.com' },
      ],
    }) : null,
    content: contentHtml,
    content_bottom: contentBelowHtml,
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  return CivicBanner({
    ...generalKnobs,
    ...getSlots([
      'content_top1',
      'breadcrumbs',
      'content_top2',
      'content_top3',
      'title',
      'content_middle',
      'content',
      'content_below',
      'content_bottom',
    ]),
  });
};
