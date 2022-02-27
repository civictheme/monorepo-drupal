import {
  boolean, radios, number, text, date,
} from '@storybook/addon-knobs';

import {
  demoImage,
  dropDownFilter,
  formElement,
  randomUrl,
} from '../../00-base/base.stories';

import DropdownFilter from '../../02-molecules/dropdown-filter/dropdown-filter.twig';
import CivicLargeFilter from '../large-filter/large-filter.twig';
import CivicBasicFilter from '../../02-molecules/basic-filter/basic-filter.twig';

import CivicCardContainer from '../card-container/card-container.twig';
import PromoCard from '../../02-molecules/promo-card/promo-card.twig';
import NavigationCard from '../../02-molecules/navigation-card/navigation-card.twig';

import CivicPagination from '../../02-molecules/pagination/pagination.twig';
import CivicListing from './listing.twig';

export default {
  title: 'Organisms/Listing',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Listing = (knobTab) => {
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
  const generalKnobs = {
    theme,
  };
  const showExposed = boolean('Show filters', true, generalKnobTab);
  const filterType = radios(
    'Filter type',
    {
      Large: 'large',
      Basic: 'basic',
    },
    'large',
    generalKnobTab,
  );
  // Show cards as promo card or navigation card.
  const viewMode = radios(
    'Card type',
    {
      'Promo card': 'promo',
      'Navigation card': 'navigation',
    },
    'promo',
    generalKnobTab,
  );
  const showItemsPerPage = boolean('Show items per page dropdown', true, generalKnobTab);
  const itemsPerPage = number(
    'Items per page',
    6,
    {
      range: true,
      min: 6,
      max: 48,
      step: 6,
    },
    generalKnobTab,
  );
  const resultNumber = number(
    'Number of results',
    6,
    {
      range: true,
      min: 0,
      max: 48,
      step: 6,
    },
    generalKnobTab,
  );

  const showPager = boolean('Show pager', true, generalKnobTab);

  // Create empty markup.
  if (resultNumber === 0) {
    generalKnobs.empty = '<p>No results found</p>';
  }

  // Build exposed filters.
  if (showExposed) {
    const filterNumber = number(
      'Number of extra filters',
      3,
      {
        range: true,
        min: 0,
        max: 5,
        step: 1,
      },
      generalKnobTab,
    );
    let count = 0;
    const filters = [];
    const basicFilterTitles = [
      'News',
      'Events',
      'Highlights',
    ];
    if (filterNumber > 0) {
      for (let i = 0; i < filterNumber; i++) {
        if (filterType === 'large') {
          const inputType = ['radio', 'checkbox'][Math.round(Math.random() * 2)];
          filters.push(dropDownFilter(inputType, 4, theme, true, count++));
        } else {
          filters.push({
            text: basicFilterTitles[i % 3],
          });
        }
      }
    }
    if (filterType === 'large') {
      generalKnobs.exposed = CivicLargeFilter({
        theme,
        filter_title: 'Filter search results by:',
        tags_title: 'Selected filters:',
        clear_text: 'Clear all',
        filters: filters.join(''),
      });
    } else {
      generalKnobs.exposed = CivicBasicFilter({
        theme,
        is_multiple: false,
        items: filters,
      });
    }
  }

  const children = [];
  for (let i = 6; i <= 48; i += 6) {
    const options = {
      title: i,
      required: false,
      description: false,
      attributes: 'name="test"',
      form_element_attributes: 'data-dropdown-filter-item',
    };
    children.push(formElement('radio', options, theme, false, i));
  }

  // Build items per page dropdown.
  if (showItemsPerPage) {
    generalKnobs.items_per_page = DropdownFilter({
      theme,
      filter_text: 'Items per page',
      options_title: 'Show number of items per page',
      options: children.join(' '),
    });
  }

  // Build pagination.
  if (showPager) {
    const pageCount = 5;
    const pages = {};
    for (let i = 0; i < pageCount; i++) {
      pages[i + 1] = {
        href: randomUrl(),
      };
    }
    generalKnobs.pager = CivicPagination({
      theme,
      heading_id: 'civic-listing-demo',
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
      ellipses: true,
      current: 1,
    });
  }

  // Build results / rows.
  if (resultNumber > 0) {
    const cardsKnobTab = 'Cards';
    const cardsProps = {
      theme: radios(
        'Theme',
        {
          Light: 'light',
          Dark: 'dark',
        },
        'light',
        cardsKnobTab,
      ),
      title: text('Title', 'Event name which runs across two or three lines', cardsKnobTab),
      date: date('Date', new Date(), cardsKnobTab),
      summary: text('Summary', 'Card summary using body copy which can run across multiple lines. Recommend limiting this summary to three or four lines..', cardsKnobTab),
      url: text('Link URL', 'http://example.com', cardsKnobTab),
      image: boolean('With image', true, cardsKnobTab) ? {
        src: demoImage(),
        alt: 'Image alt text',
      } : false,
      size: 'large',
    };

    cardsProps.date = new Date(cardsProps.date).toLocaleDateString('en-uk', {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
    });
    const cards = [];
    const cardsCount = itemsPerPage > resultNumber ? resultNumber : itemsPerPage;
    const Card = viewMode === 'promo' ? PromoCard : NavigationCard;
    for (let itr = 0; itr < cardsCount; itr += 1) {
      cards.push(Card(cardsProps));
    }

    generalKnobs.rows = CivicCardContainer({
      theme,
      cards,
      column_count: viewMode === 'promo' ? 3 : 2,
      fill_width: false,
      with_spacing: 'both',
    });
  }

  return CivicListing({
    theme,
    ...generalKnobs,
  });
};
