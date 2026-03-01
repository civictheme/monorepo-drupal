// phpcs:ignoreFile
import GroupFilter from '../../02-molecules/group-filter/group-filter.twig';
import GroupFilterData from '../../02-molecules/group-filter/group-filter.stories.data';
import SingleFilter from '../../02-molecules/single-filter/single-filter.twig';
import SingleFilterData from '../../02-molecules/single-filter/single-filter.stories.data';
import Grid from '../../00-base/grid/grid.twig';
import PromoCard from '../../02-molecules/promo-card/promo-card.twig';
import PromoCardData from '../../02-molecules/promo-card/promo-card.stories.data';
import EventCard from '../../02-molecules/event-card/event-card.twig';
import EventCardData from '../../02-molecules/event-card/event-card.stories.data';
import NavigationCard from '../../02-molecules/navigation-card/navigation-card.twig';
import NavigationCardData from '../../02-molecules/navigation-card/navigation-card.stories.data';
import Snippet from '../../02-molecules/snippet/snippet.twig';
import SnippetData from '../../02-molecules/snippet/snippet.stories.data';
import Paragraph from '../../01-atoms/paragraph/paragraph.twig';
import Pagination from '../../02-molecules/pagination/pagination.twig';
import PaginationData from '../../02-molecules/pagination/pagination.stories.data';

export default {
  args(theme = 'light', options = {}, overrides = {}) {
    const items = this.items(theme, options);
    const groupOptions = options.selectedFilters === true ? { selectedFilters: true } : undefined;
    return {
      theme,
      title: 'My List Title',
      link_above: {
        text: 'View more',
        url: 'http://www.example.com',
        title: 'View more',
        is_new_window: false,
        is_external: false,
      },
      content: 'Example content',
      filters: options.group ? GroupFilter(GroupFilterData.args(theme, groupOptions)) : SingleFilter(SingleFilterData.args(theme)),
      results_count: 'Showing 1 of 6',
      rows_above: Paragraph({
        theme,
        content: 'Example content above rows',
      }),
      rows: items.length > 0 ? Grid({
        theme,
        items,
        template_column_count: options.columnCount || 3,
        auto_breakpoint: options.columnAutoBreakpoint,
        fill_width: false,
        with_background: false,
        row_class: 'row--equal-heights-content row--vertically-spaced',
      }) : null,
      rows_below: Paragraph({
        theme,
        content: `Example content below rows`,
      }),
      empty: '<p>No results found</p>',
      pagination: Pagination(PaginationData.args(theme)),
      footer: '',
      link_below: {
        text: 'View more',
        url: 'http://www.example.com',
        title: 'View more',
        is_new_window: false,
        is_external: false,
      },
      vertical_spacing: 'none',
      with_background: false,
      attributes: null,
      modifier_class: '',
      ...overrides,
    };
  },
  items(theme = 'light', options = {}) {
    const components = {
      promo: { data: PromoCardData.args(theme), render: PromoCard },
      event: { data: EventCardData.args(theme), render: EventCard },
      navigation: { data: NavigationCardData.args(theme), render: NavigationCard },
      snippet: { data: SnippetData.args(theme), render: Snippet },
    };
    const component = options.component || 'promo';
    const { render } = components[component];
    const defaultData = components[component].data;
    const itemData = options.items || Array.from(Array(6), () => ({}));
    return itemData.map((data) => render({ ...defaultData, ...data }));
  },
  basicOverrides(overrides = {}) {
    return {
      title: null,
      link_above: null,
      content: null,
      filters: null,
      results_count: null,
      rows_above: null,
      rows_below: null,
      empty: null,
      pagination: null,
      footer: null,
      link_below: null,
      ...overrides,
    };
  },
};
