// phpcs:ignoreFile
import {
  boolean, number, radios, text,
} from '@storybook/addon-knobs';
import CivicThemePagination from './pagination.twig';
import { randomUrl } from '../../00-base/base.utils';

export default {
  title: 'Molecules/Pagination',
};

export const Pagination = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const theme = radios(
    'Theme',
    {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
    generalKnobTab,
  );
  const activeIsLink = boolean('Active is a link', true, generalKnobTab);

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

  const current = number(
    'Current page',
    Math.max(1, Math.floor(pageCount / 2)),
    {
      range: true,
      min: 1,
      max: pageCount,
      step: 1,
    },
    generalKnobTab,
  );
  const useEllipsis = boolean('With ellipsis', false, generalKnobTab);

  const pages = {};
  const pagerMiddle = Math.ceil(pageCount / 2);
  const pagerFirst = current - pagerMiddle + 1;
  const pagerLast = current + pageCount - pagerMiddle;
  for (let i = 0; i < pageCount; i++) {
    if (useEllipsis) {
      if (i === 0 || (i > pagerFirst && i < pagerLast) || i === (pageCount - 1)) {
        pages[i + 1] = {
          href: randomUrl(),
        };
      }
    } else {
      pages[i + 1] = {
        href: randomUrl(),
      };
    }
  }

  const generalKnobs = {
    theme,
    active_is_link: activeIsLink,
    items: pageCount > 0 ? {
      previous: {
        href: randomUrl(),
      },
      pages,
      next: {
        href: randomUrl(),
      },
    } : null,
    heading_id: text('Heading Id', 'ct-pager-demo', generalKnobTab),
    use_ellipsis: useEllipsis,
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
    total_pages: pageCount,
    current,
    modifier_class: text('Additional classes', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemePagination({
    ...generalKnobs,
  });
};
