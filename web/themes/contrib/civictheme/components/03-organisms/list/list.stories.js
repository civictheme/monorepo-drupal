// phpcs:ignoreFile
import {
  boolean, number, radios, text,
} from '@storybook/addon-knobs';

import {
  demoImage,
  randomFormElements, randomInt, randomName, randomSentence, randomString, randomTags,
  randomUrl,
} from '../../00-base/base.utils';

import CivicThemeGroupFilter
  from '../../02-molecules/group-filter/group-filter.twig';
import CivicThemeSingleFilter
  from '../../02-molecules/single-filter/single-filter.twig';
import CivicThemeItemGrid from '../../00-base/item-grid/item-grid.twig';
import PromoCard
  from '../../02-molecules/promo-card/promo-card.twig';
import NavigationCard
  from '../../02-molecules/navigation-card/navigation-card.twig';
import Snippet
  from '../../02-molecules/snippet/snippet.twig';
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
  const itemsKnobTab = 'List items';

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
  const showItems = boolean('Show items', true, generalKnobTab);
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
            title: `Filter ${randomString(randomInt(3, 8))} ${j + 1}`,
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

  // Build items.
  if (showItems) {
    const resultNumber = number(
      'Number of results',
      6,
      {
        range: true,
        min: 0,
        max: 48,
        step: 6,
      },
      itemsKnobTab,
    );

    // Create markup for no results.
    if (resultNumber === 0) {
      generalKnobs.empty = '<p>No results found</p>';
    }

    const viewItemAs = radios(
      'Item type',
      {
        'Promo card': 'promo-card',
        'Navigation card': 'navigation-card',
        Snippet: 'snippet',
      },
      'promo-card',
      itemsKnobTab,
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
      itemsKnobTab,
    );

    if (resultNumber > 0) {
      const itemTheme = radios(
        'Theme',
        {
          Light: 'light',
          Dark: 'dark',
        },
        'light',
        itemsKnobTab,
      );
      const itemWithImage = boolean('With image', true, itemsKnobTab);
      const itemTags = randomTags(number(
        'Number of tags',
        2,
        {
          range: true,
          min: 0,
          max: 10,
          step: 1,
        },
        itemsKnobTab,
      ), true);

      let itemComponentInstance;
      let columnCount;
      switch (viewItemAs) {
        case 'promo-card':
          itemComponentInstance = PromoCard;
          columnCount = 3;
          break;
        case 'navigation-card':
          itemComponentInstance = NavigationCard;
          columnCount = 2;
          break;
        case 'snippet':
          itemComponentInstance = Snippet;
          columnCount = 1;
          break;
        default:
          itemComponentInstance = PromoCard;
      }

      const items = [];
      const itemsCount = itemsPerPage > resultNumber ? resultNumber : itemsPerPage;
      for (let i = 0; i < itemsCount; i++) {
        const itemProps = {
          theme: itemTheme,
          title: `Title ${randomSentence(randomInt(1, 5))}`,
          date: new Date().toLocaleDateString('en-uk', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
          }),
          summary: `Summary ${randomSentence(randomInt(15, 25))}`,
          url: randomUrl(),
          image: itemWithImage ? {
            url: demoImage(),
            alt: 'Image alt text',
          } : false,
          size: 'large',
          tags: itemTags,
        };

        items.push(itemComponentInstance(itemProps));
      }

      generalKnobs.rows = CivicThemeItemGrid({
        theme,
        items,
        column_count: columnCount,
        fill_width: false,
        with_background: generalKnobs.with_background,
      });

      generalKnobs.results_count = boolean('With result count', true, generalKnobTab) ? `Showing ${itemsCount} of ${resultNumber}` : null;
      generalKnobs.rows_above = boolean('With content above rows', true, generalKnobTab) ? `Example content above rows ${randomSentence(randomInt(10, 75))}` : null;
      generalKnobs.rows_below = boolean('With content below rows', true, generalKnobTab) ? `Example content below rows${randomSentence(randomInt(10, 75))}` : null;
    }
  }

  return CivicThemeList({
    theme,
    ...generalKnobs,
  });
};
