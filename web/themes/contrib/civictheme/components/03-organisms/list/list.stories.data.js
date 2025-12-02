import {
  demoImage,
  randomFormElements,
  randomInt,
  randomName,
  randomSentence,
  randomString,
  randomTags,
  randomUrl,
} from '../../00-base/base.utils';

import CivicThemeGroupFilter from '../../02-molecules/group-filter/group-filter.twig';
import CivicThemeSingleFilter from '../../02-molecules/single-filter/single-filter.twig';
import CivicThemeItemGrid from '../../00-base/item-grid/item-grid.twig';
import PromoCard from '../../02-molecules/promo-card/promo-card.twig';
import NavigationCard from '../../02-molecules/navigation-card/navigation-card.twig';
import Snippet from '../../02-molecules/snippet/snippet.twig';
import CivicThemePagination from '../../02-molecules/pagination/pagination.twig';

const generateFilters = (theme, filterType, filtersCount) => {
  if (filtersCount === 0) {
    return null;
  }

  if (filterType === 'single') {
    const name = randomName(5);
    const items = [];
    for (let i = 0; i < filtersCount; i++) {
      items.push({
        text: `Filter ${i + 1}${randomString(3)}`,
        name: name + (i + 1),
        attributes: `id="${name}_${randomName(3)}_${i + 1}"`,
      });
    }

    return CivicThemeSingleFilter({
      theme,
      is_multiple: true,
      items,
    });
  }

  const filters = [];
  for (let j = 0; j < filtersCount; j++) {
    filters.push({
      content: randomFormElements(1, theme, true)[0],
      title: `Filter ${randomString(randomInt(3, 8))} ${j + 1}`,
    });
  }

  return CivicThemeGroupFilter({
    theme,
    title: 'Filter search results by:',
    filters,
  });
};

const generatePagination = (theme) => {
  const pageCount = 5;
  const pages = {};
  for (let i = 0; i < pageCount; i++) {
    pages[i + 1] = {
      href: randomUrl(),
    };
  }

  return CivicThemePagination({
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
      { type: 'option', label: 10, value: 10, selected: false },
      { type: 'option', label: 20, value: 20, selected: true },
      { type: 'option', label: 50, value: 50, selected: false },
      { type: 'option', label: 100, value: 100, selected: false },
    ],
  });
};

const generateItems = (theme, itemType, itemCount, withImage, withBackground) => {
  if (itemCount === 0) {
    return { rows: null, empty: '<p>No results found</p>' };
  }

  let itemComponentInstance;
  let columnCount;

  switch (itemType) {
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
      columnCount = 3;
  }

  const items = [];
  const itemTags = randomTags(2, true);

  for (let i = 0; i < itemCount; i++) {
    const itemProps = {
      theme,
      title: `Title ${randomSentence(randomInt(1, 5))}`,
      date: new Date().toLocaleDateString('en-uk', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
      }),
      summary: `Summary ${randomSentence(randomInt(15, 25))}`,
      url: randomUrl(),
      image: withImage ? {
        url: demoImage(),
        alt: 'Image alt text',
      } : false,
      size: 'large',
      tags: itemTags,
    };

    items.push(itemComponentInstance(itemProps));
  }

  return {
    rows: CivicThemeItemGrid({
      theme,
      items,
      column_count: columnCount,
      fill_width: false,
      with_background: withBackground,
    }),
    empty: null,
  };
};

export default {
  args: (theme = 'light') => {
    const withBackground = false;
    const itemResult = generateItems(theme, 'promo-card', 6, true, withBackground);

    return {
      theme,
      title: 'List title',
      content: randomSentence(50),
      link_above: {
        text: 'View more',
        url: 'http://www.example.com',
        title: 'View more',
        is_new_window: false,
        is_external: false,
      },
      link_below: {
        text: 'View more',
        url: 'http://www.example.com',
        title: 'View more',
        is_new_window: false,
        is_external: false,
      },
      vertical_spacing: 'none',
      with_background: withBackground,
      filters: generateFilters(theme, 'single', 3),
      pager: generatePagination(theme),
      rows: itemResult.rows,
      empty: itemResult.empty,
      results_count: 'Showing 6 of 6',
      rows_above: `Example content above rows ${randomSentence(randomInt(10, 75))}`,
      rows_below: `Example content below rows ${randomSentence(randomInt(10, 75))}`,
      modifier_class: '',
    };
  },
};
