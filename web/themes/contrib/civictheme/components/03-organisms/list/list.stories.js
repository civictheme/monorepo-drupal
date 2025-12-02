import {
  demoImage,
  randomFormElements, randomInt, randomName, randomSentence, randomString, randomTags,
  randomUrl,
} from '../../00-base/base.utils';

import CivicThemeGroupFilter from '../../02-molecules/group-filter/group-filter.twig';
import CivicThemeSingleFilter from '../../02-molecules/single-filter/single-filter.twig';
import CivicThemeItemGrid from '../../00-base/item-grid/item-grid.twig';
import PromoCard from '../../02-molecules/promo-card/promo-card.twig';
import NavigationCard from '../../02-molecules/navigation-card/navigation-card.twig';
import Snippet from '../../02-molecules/snippet/snippet.twig';
import CivicThemePagination from '../../02-molecules/pagination/pagination.twig';
import CivicThemeList from './list.twig';

const meta = {
  title: 'Organisms/List',
  component: CivicThemeList,
  parameters: {
    layout: 'fullscreen',
  },
  render: (args) => {
    const result = { ...args };

    // Build filters
    if (args.show_filters) {
      if (args.filter_type === 'single') {
        const name = randomName(5);
        const items = [];
        for (let i = 0; i < args.filters_count; i++) {
          items.push({
            text: `Filter ${i + 1}${randomString(3)}`,
            name,
            attributes: `id="${name}_${randomName(3)}_${i + 1}"`,
          });
        }
        result.filters = CivicThemeSingleFilter({
          theme: args.theme,
          is_multiple: true,
          items,
        });
      } else {
        const filters = [];
        for (let j = 0; j < args.filters_count; j++) {
          filters.push({
            content: randomFormElements(1, args.theme, true)[0],
            title: `Filter ${randomString(randomInt(3, 8))} ${j + 1}`,
          });
        }
        result.filters = CivicThemeGroupFilter({
          theme: args.theme,
          title: 'Filter search results by:',
          filters,
        });
      }
    }

    // Build pagination
    if (args.show_pager) {
      const pageCount = 5;
      const pages = {};
      for (let i = 0; i < pageCount; i++) {
        pages[i + 1] = { href: randomUrl() };
      }
      result.pager = CivicThemePagination({
        theme: args.theme,
        heading_id: 'ct-listing-demo',
        items: {
          previous: { text: 'Previous', href: randomUrl() },
          pages,
          next: { text: 'Next', href: randomUrl() },
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
    }

    // Build items
    if (args.show_items && args.result_number > 0) {
      const itemTags = randomTags(args.tag_count, true);
      let itemComponentInstance;
      let columnCount;

      switch (args.item_type) {
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
      const itemsCount = Math.min(args.items_per_page, args.result_number);
      for (let i = 0; i < itemsCount; i++) {
        items.push(itemComponentInstance({
          theme: args.item_theme,
          title: `Title ${randomSentence(randomInt(1, 5))}`,
          date: new Date().toLocaleDateString('en-uk', { year: 'numeric', month: 'short', day: 'numeric' }),
          summary: `Summary ${randomSentence(randomInt(15, 25))}`,
          url: randomUrl(),
          image: args.with_image ? { url: demoImage(), alt: 'Image alt text' } : false,
          size: 'large',
          tags: itemTags,
        }));
      }

      result.rows = CivicThemeItemGrid({
        theme: args.theme,
        items,
        column_count: columnCount,
        fill_width: false,
        with_background: args.with_background,
      });

      if (args.with_result_count) {
        result.results_count = `Showing ${itemsCount} of ${args.result_number}`;
      }
    } else if (args.show_items && args.result_number === 0) {
      result.empty = '<p>No results found</p>';
    }

    return CivicThemeList(result);
  },
  argTypes: {
    theme: { control: { type: 'radio' }, options: ['light', 'dark'] },
    title: { control: { type: 'text' } },
    content: { control: { type: 'text' } },
    vertical_spacing: { control: { type: 'radio' }, options: ['none', 'top', 'bottom', 'both'] },
    with_background: { control: { type: 'boolean' } },
    show_filters: { control: { type: 'boolean' } },
    filter_type: { control: { type: 'radio' }, options: ['single', 'group'] },
    filters_count: { control: { type: 'range', min: 0, max: 15, step: 1 } },
    show_items: { control: { type: 'boolean' } },
    result_number: { control: { type: 'range', min: 0, max: 48, step: 6 } },
    items_per_page: { control: { type: 'range', min: 6, max: 48, step: 6 } },
    item_type: { control: { type: 'select' }, options: ['promo-card', 'navigation-card', 'snippet'] },
    item_theme: { control: { type: 'radio' }, options: ['light', 'dark'] },
    with_image: { control: { type: 'boolean' } },
    tag_count: { control: { type: 'range', min: 0, max: 10, step: 1 } },
    with_result_count: { control: { type: 'boolean' } },
    show_pager: { control: { type: 'boolean' } },
    modifier_class: { control: { type: 'text' } },
  },
};

export default meta;

export const List = {
  args: {
    theme: 'light',
    title: 'List title',
    content: randomSentence(50),
    vertical_spacing: 'none',
    with_background: false,
    show_filters: true,
    filter_type: 'single',
    filters_count: 3,
    show_items: true,
    result_number: 6,
    items_per_page: 6,
    item_type: 'promo-card',
    item_theme: 'light',
    with_image: true,
    tag_count: 2,
    with_result_count: true,
    show_pager: true,
    modifier_class: '',
  },
};
