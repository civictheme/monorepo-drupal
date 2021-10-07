import {
  radios, text, object, boolean,
} from '@storybook/addon-knobs';
import CivicPagination from './pagination.twig';
import './pagination.scss';

export default {
  title: 'Molecule/Pagination',
};

export const Pagination = () => {
  const generalKnobTab = 'General';
  return CivicPagination({
    theme: radios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      generalKnobTab,
    ),
    heading_id: text('Heading Id', 'civic-pager-demo', generalKnobTab),
    items: object('Pagination items', {
      previous: {
        text: 'Previous',
        href: '#',
      },
      pages: {
        1: {
          href: '#',
        },
        2: {
          href: '#',
        },
        3: {
          href: '#',
        },
        4: {
          href: '#',
        },
        5: {
          href: '#',
        },
      },
      next: {
        text: 'Next',
        href: '#',
      },
    }, generalKnobTab),
    ellipses: boolean('With ellipses', true, generalKnobTab) ? {
      previous: 0,
      next: 1,
    } : false,
    current: text('Current Page', 2, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  });
};
