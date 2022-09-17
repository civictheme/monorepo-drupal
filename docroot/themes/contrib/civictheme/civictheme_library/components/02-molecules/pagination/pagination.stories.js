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
    items_per_page_options: boolean('With items per page', true, generalKnobTab) ? [
      {
        type: 'option', label: 10, value: 10, selected: false,
      },
      {
        type: 'option', label: 20, value: 20, selected: true,
      },
      {
        type: 'option', label: 50, value: 50, selected: false,
      },
      {
        type: 'option', label: 100, value: 100, selected: false,
      },
    ] : null,
    current: number(
      'Current page',
      Math.max(1, Math.floor(pageCount / 2)),
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

  return CivicThemePagination({
    ...generalKnobs,
  });
};
