// phpcs:ignoreFile
import {
  boolean, date, number, radios, text,
} from '@storybook/addon-knobs';

import {
  demoImage,
  randomFormElements, randomName, randomSentence, randomString,
  randomUrl,
} from '../../00-base/base.utils';

import CivicThemeGroupFilter
  from '../../02-molecules/group-filter/group-filter.twig';
import CivicThemeSingleFilter
  from '../../02-molecules/single-filter/single-filter.twig';

import CivicThemeItemGrid from '../../00-base/item-grid/item-grid.twig';
import PromoCard from '../../02-molecules/promo-card/promo-card.twig';
import NavigationCard
  from '../../02-molecules/navigation-card/navigation-card.twig';

import CivicThemePagination
  from '../../02-molecules/pagination/pagination.twig';
import CivicThemeList from './list.twig';

export default {
  title: 'Organisms/List',
  parameters: {
    layout: 'fullscreen',
  },
};

export const List = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';
  const filtersKnobTab = 'Filters';
  const cardsKnobTab = 'Cards';

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
    title: text('Title', 'List title', generalKnobTab),
  };

  generalKnobs.content = boolean('With content', true, generalKnobTab) ? randomSentence(50) : null;
  generalKnobs.link_above = boolean('With link above', true, generalKnobTab) ? {
    text: text('Link above text', 'View more', generalKnobTab),
    url: 'http://www.example.com',
    title: 'View more',
    is_new_window: false,
    is_external: false,
  } : null;
  generalKnobs.link_below = boolean('With link below', true, generalKnobTab) ? {
    text: text('Link below text', 'View more', generalKnobTab),
    url: 'http://www.example.com',
    title: 'View more',
    is_new_window: false,
    is_external: false,
  } : null;
  generalKnobs.vertical_spacing = radios(
    'Vertical spacing',
    {
      None: 'none',
      Top: 'top',
      Bottom: 'bottom',
      Both: 'both',
    },
    'none',
    generalKnobTab,
  );
  generalKnobs.with_background = boolean('With background', false, generalKnobTab);
  generalKnobs.modifier_class = text('Additional class', '', generalKnobTab);

  const showFilters = boolean('Show filters', true, generalKnobTab);
  const showCards = boolean('Show cards', true, generalKnobTab);
  const showPager = boolean('Show pager', true, generalKnobTab);

  let filtersCount = 0;

  // Build filters.
  if (showFilters) {
    const filterType = radios(
      'Filter type',
      {
        Single: 'single',
        Group: 'group',
      },
      'single',
      filtersKnobTab,
    );

    filtersCount = number(
      'Number of filters',
      3,
      {
        range: true,
        min: 0,
        max: 15,
        step: 1,
      },
      filtersKnobTab,
    );

    if (filterType === 'single') {
      const name = randomName(5);
      const items = [];
      if (filtersCount > 0) {
        for (let i = 0; i < filtersCount; i++) {
          items.push({
            text: `Filter ${i + 1}${randomString(3)}`,
            name: generalKnobs.is_multiple ? name + (i + 1) : name,
            attributes: `id="${name}_${randomName(3)}_${i + 1}"`,
          });
        }
      }

      generalKnobs.filters = CivicThemeSingleFilter({
        theme,
        is_multiple: true,
        items,
      });
    } else {
      const filters = [];
      if (filtersCount > 0) {
        for (let j = 0; j < filtersCount; j++) {
          filters.push({
            content: randomFormElements(1, generalKnobs.theme, true)[0],
            title: `Filter ${j + 1}`,
          });
        }
      }

      generalKnobs.filters = CivicThemeGroupFilter({
        theme,
        title: 'Filter search results by:',
        filters,
      });
    }
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
    generalKnobs.pager = CivicThemePagination({
      theme,
      heading_id: 'ct-listing-demo',
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
      items_per_page_options: [
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
      ],
    });
  }

  if (showCards) {
    const resultNumber = number(
      'Number of results',
      6,
      {
        range: true,
        min: 0,
        max: 48,
        step: 6,
      },
      cardsKnobTab,
    );

    // Create empty markup.
    if (resultNumber === 0) {
      generalKnobs.empty = '<p>No results found</p>';
    }

    const viewItemAs = radios(
      'Card type',
      {
        'Promo card': 'promo',
        'Navigation card': 'navigation',
      },
      'promo',
      cardsKnobTab,
    );

    const itemsPerPage = number(
      'Items per page',
      6,
      {
        range: true,
        min: 6,
        max: 48,
        step: 6,
      },
      cardsKnobTab,
    );

    // Build results / rows.
    if (resultNumber > 0) {
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
          url: demoImage(),
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
      const Card = viewItemAs === 'promo' ? PromoCard : NavigationCard;
      for (let itr = 0; itr < cardsCount; itr += 1) {
        cards.push(Card(cardsProps));
      }

      generalKnobs.rows = CivicThemeItemGrid({
        theme,
        items: cards,
        column_count: viewItemAs === 'promo' ? 3 : 2,
        fill_width: false,
        with_background: generalKnobs.with_background,
      });

      generalKnobs.rows_above = `Showing ${cardsCount} of ${resultNumber}`;
      generalKnobs.rows_below = boolean('With content below rows', true, generalKnobTab) ? 'Example content below rows' : null;
    }
  }

  return CivicThemeList({
    theme,
    ...generalKnobs,
  });
};
