import GroupFilter from '../../02-molecules/group-filter/group-filter.twig';
import GroupFilterData from '../../02-molecules/group-filter/group-filter.stories.data';
import SingleFilter from '../../02-molecules/single-filter/single-filter.twig';
import SingleFilterData from '../../02-molecules/single-filter/single-filter.stories.data';
import Grid from '../../00-base/grid/grid.twig';
import PromoCard from '../../02-molecules/promo-card/promo-card.twig';
import PromoCardData from '../../02-molecules/promo-card/promo-card.stories.data';
import NavigationCard from '../../02-molecules/navigation-card/navigation-card.twig';
import NavigationCardData from '../../02-molecules/navigation-card/navigation-card.stories.data';
import Snippet from '../../02-molecules/snippet/snippet.twig';
import SnippetData from '../../02-molecules/snippet/snippet.stories.data';
import Paragraph from '../../01-atoms/paragraph/paragraph.twig';
import Pagination from '../../02-molecules/pagination/pagination.twig';
import PaginationData from '../../02-molecules/pagination/pagination.stories.data';

export default {
  args: (theme = 'light', options = {}) => {
    const listComponents = {
      promo: () => PromoCard(PromoCardData.args('light')),
      navigation: () => NavigationCard(NavigationCardData.args('light')),
      snippet: () => Snippet(SnippetData.args(theme)),
    };
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
      filters: options.group ? GroupFilter(GroupFilterData.args(theme)) : SingleFilter(SingleFilterData.args(theme)),
      results_count: 'Showing 1 of 6',
      rows_above: Paragraph({
        theme,
        content: 'Example content above rows',
        allow_html: true,
      }),
      rows: Grid({
        theme,
        items: [1, 2, 3, 4, 5, 6].map(options.component ? listComponents[options.component] : listComponents.promo),
        template_column_count: options.columnCount || 3,
        fill_width: false,
        with_background: false,
        row_class: 'row--equal-heights-content row--vertically-spaced',
      }),
      rows_below: Paragraph({
        theme,
        content: `Example content below rows`,
        allow_html: true,
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
      attributes: '',
      modifier_class: '',
    };
  },
};
