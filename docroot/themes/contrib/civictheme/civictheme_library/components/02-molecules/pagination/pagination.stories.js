// phpcs:ignoreFile
import {
  boolean, number, radios, text,
} from '@storybook/addon-knobs';
import CivicThemePagination from './pagination.twig';
import { randomUrl } from '../../00-base/base.stories';

export default {
  title: 'Molecules/Pagination',
};

export const Pagination = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const pagerType = radios('Pager type', {
    Default: 'default',
    Full: 'full',
    Mini: 'mini',
  }, 'default', generalKnobTab);

  const pageCount = number(
    'Count of pages',
    5,
    {
      range: true,
      min: 0,
      max: 10,
      step: 1,
    },
    generalKnobTab,
  );

  const pages = {};
  if (pagerType !== 'mini') {
    for (let i = 0; i < pageCount; i++) {
      pages[i + 1] = {
        href: randomUrl(),
      };
    }
  }

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
    active_is_link: boolean('Active is a link', true, generalKnobTab),
    items: pageCount > 0 ? {
      previous: {
        href: randomUrl(),
      },
      pages,
      next: {
        href: randomUrl(),
      },
    } : null,
    heading_id: text('Heading Id', 'civictheme-pager-demo', generalKnobTab),
    ellipses: boolean('With ellipses', true, generalKnobTab) ? {
      previous: 0,
      next: 1,
    } : false,
    items_per_page: boolean('With items per page', true, generalKnobTab) ? {
      10: 10,
      20: 20,
      50: 50,
      100: 100,
    } : null,
    current: number(
      'Current page',
      1,
      {
        range: true,
        min: 1,
        max: pageCount,
        step: 1,
      },
      generalKnobTab,
    ),
    total_pages: pageCount,
    attributes: text('Additional attributes', '', generalKnobTab),
    modifier_class: text('Additional classes', '', generalKnobTab),
  };

  if (pagerType === 'full') {
    generalKnobs.items.first = {
      href: randomUrl(),
    };
    generalKnobs.items.last = {
      href: randomUrl(),
    };
  }

  return CivicThemePagination({
    ...generalKnobs,
  });
};
