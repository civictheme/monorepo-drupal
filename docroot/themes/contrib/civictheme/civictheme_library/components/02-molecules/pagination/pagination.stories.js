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

  const pageCount = number(
    'Number of pages',
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
  for (let i = 0; i < pageCount; i++) {
    pages[i + 1] = {
      href: randomUrl(),
    };
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
    heading_id: text('Heading Id', 'civictheme-pager-demo', generalKnobTab),
    items: {
      previous: {
        text: 'Previous',
        href: randomUrl(),
      },
      pages,
      next: {
        text: 'Next',
        href: randomUrl(),
      },
    },
    ellipses: boolean('With ellipses', true, generalKnobTab) ? {
      previous: 0,
      next: 1,
    } : false,
    current: number(
      'Current page',
      Math.floor(pageCount / 2),
      {
        range: true,
        min: 1,
        max: pageCount,
        step: 1,
      },
      generalKnobTab,
    ),
    total_pages: pageCount,
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  return CivicThemePagination({
    ...generalKnobs,
  });
};
