// phpcs:ignoreFile
import { demoImage, knobBoolean, knobNumber, knobRadios, knobText, KnobValues, randomBool, randomFutureDate, randomInt, randomName, randomSentence, randomString, randomTags, randomText, randomUrl, shouldRender } from '../../00-base/storybook/storybook.utils';

import CivicThemeGroupFilter from '../../02-molecules/group-filter/group-filter.twig';
import CivicThemeSingleFilter from '../../02-molecules/single-filter/single-filter.twig';
import CivicThemeGrid from '../../00-base/grid/grid.twig';
import PromoCard from '../../02-molecules/promo-card/promo-card.twig';
import NavigationCard from '../../02-molecules/navigation-card/navigation-card.twig';
import Snippet from '../../02-molecules/snippet/snippet.twig';
import CivicThemeList from './list.twig';
import { Pagination } from '../../02-molecules/pagination/pagination.stories';
import { randomFields } from '../../02-molecules/field/field.utils';
import { Paragraph } from '../../01-atoms/paragraph/paragraph.stories';

export default {
  title: 'Organisms/List',
  parameters: {
    layout: 'fullscreen',
  },
};

export const List = (parentKnobs = {}) => {
  const knobs = {
    theme: knobRadios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      parentKnobs.theme,
      parentKnobs.knobTab,
    ),
    title: knobText('Title', 'List title', parentKnobs.title, parentKnobs.knobTab),
    content: knobBoolean('With content', true, parentKnobs.with_content, parentKnobs.knobTab) ? randomSentence(50) : null,
  };

  knobs.link_above = knobBoolean('With link above', true, parentKnobs.with_link_above, parentKnobs.knobTab) ? {
    text: knobText('Link above text', 'View more', parentKnobs.link_above_text, 'Content'),
    url: 'http://www.example.com',
    title: 'View more',
    is_new_window: false,
    is_external: false,
  } : null;

  knobs.link_below = knobBoolean('With link below', true, parentKnobs.with_link_below, parentKnobs.knobTab) ? {
    text: knobText('Link below text', 'View more', parentKnobs.link_below_text, 'Content'),
    url: 'http://www.example.com',
    title: 'View more',
    is_new_window: false,
    is_external: false,
  } : null;

  knobs.vertical_spacing = knobRadios(
    'Vertical spacing',
    {
      None: 'none',
      Top: 'top',
      Bottom: 'bottom',
      Both: 'both',
    },
    'none',
    parentKnobs.vertical_spacing,
    parentKnobs.knobTab,
  );

  knobs.with_background = knobBoolean('With background', false, parentKnobs.with_background, parentKnobs.knobTab);

  knobs.modifier_class = knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab);

  const showFilters = knobBoolean('Show filters', true, parentKnobs.show_filter, parentKnobs.knobTab);
  const showItems = knobBoolean('Show items', true, parentKnobs.show_items, parentKnobs.knobTab);
  const showPagination = knobBoolean('Show pagination', true, parentKnobs.show_pagination, parentKnobs.knobTab);

  let filtersCount = 0;

  // Build filters.
  if (showFilters) {
    const filtersKnobTab = 'Filters';

    const filterType = knobRadios(
      'Filter type',
      {
        Single: 'single',
        Group: 'group',
      },
      'single',
      parentKnobs.filter_type,
      filtersKnobTab,
    );

    filtersCount = knobNumber(
      'Number of filters',
      3,
      {
        range: true,
        min: 0,
        max: 15,
        step: 1,
      },
      parentKnobs.number_of_filters,
      filtersKnobTab,
    );

    if (filterType === 'single') {
      const name = randomName(5);
      const items = [];
      if (filtersCount > 0) {
        for (let i = 0; i < filtersCount; i++) {
          items.push({
            text: `Filter ${i + 1}${randomString(3)}`,
            name: knobs.is_multiple ? name + (i + 1) : name,
            attributes: `id="${name}_${randomName(3)}_${i + 1}"`,
          });
        }
      }

      const filterKnobs = {
        theme: knobs.theme,
        is_multiple: true,
        items,
      };

      knobs.filters = shouldRender(parentKnobs) ? CivicThemeSingleFilter(filterKnobs) : filterKnobs;
    } else {
      const filters = [];
      if (filtersCount > 0) {
        for (let j = 0; j < filtersCount; j++) {
          filters.push({
            content: randomFields(1, knobs.theme, true)[0],
            title: `Filter ${randomString(randomInt(3, 8))} ${j + 1}`,
          });
        }
      }

      const filterKnobs = {
        theme: knobs.theme,
        title: 'Filter results by:',
        filters,
      };

      knobs.filters = shouldRender(parentKnobs) ? CivicThemeGroupFilter(filterKnobs) : filterKnobs;
    }
  }

  // Build pagination.
  if (showPagination) {
    const paginationKnobs = {
      theme: knobs.theme,
      heading_id: 'ct-listing-demo',
    };

    knobs.pagination = shouldRender(parentKnobs) ? Pagination({ ...paginationKnobs, ...{ knobTab: 'Pagination' } }) : paginationKnobs;
  }

  // Build items.
  if (showItems) {
    const itemsKnobTab = 'List items';

    const resultNumber = knobNumber(
      'Number of results',
      6,
      {
        range: true,
        min: 0,
        max: 48,
        step: 1,
      },
      parentKnobs.number_of_results,
      itemsKnobTab,
    );

    // Create markup for no results.
    if (resultNumber === 0) {
      knobs.empty = '<p>No results found</p>';
    }

    const viewItemAs = knobRadios(
      'Item type',
      {
        'Promo card': 'promo-card',
        'Navigation card': 'navigation-card',
        Snippet: 'snippet',
      },
      'promo-card',
      parentKnobs.item_type,
      itemsKnobTab,
    );

    const itemsPerPage = knobNumber(
      'Items per page',
      6,
      {
        range: true,
        min: 6,
        max: 48,
        step: 6,
      },
      parentKnobs.items_per_page,
      itemsKnobTab,
    );

    if (resultNumber > 0) {
      const itemTheme = knobRadios(
        'Theme',
        {
          Light: 'light',
          Dark: 'dark',
        },
        'light',
        parentKnobs.item_theme,
        itemsKnobTab,
      );

      const columnCount = knobNumber(
        'Number of columns',
        3,
        {
          range: true,
          min: 1,
          max: 4,
          step: 1,
        },
        parentKnobs.item_column_count,
        itemsKnobTab,
      );

      const itemWithImage = knobBoolean('With image', true, parentKnobs.item_with_image, itemsKnobTab);

      const itemTags = randomTags(knobNumber(
        'Number of tags',
        2,
        {
          range: true,
          min: 0,
          max: 10,
          step: 1,
        },
        parentKnobs.item_number_of_tags,
        itemsKnobTab,
      ), true);

      let itemComponentInstance;
      switch (viewItemAs) {
        case 'promo-card':
          itemComponentInstance = PromoCard;
          break;
        case 'navigation-card':
          itemComponentInstance = NavigationCard;
          break;
        case 'snippet':
          itemComponentInstance = Snippet;
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
          date: randomFutureDate(),
          summary: `Summary ${randomSentence(randomInt(10, 35))}`,
          link: {
            url: randomUrl(),
            is_new_window: randomBool(),
            is_external: randomBool(0.8),
          },
          image: itemWithImage ? {
            url: demoImage(),
            alt: 'Image alt text',
          } : false,
          size: 'large',
          tags: itemTags,
        };

        items.push(itemComponentInstance(itemProps));
      }

      const itemsKnobs = {
        theme: knobs.theme,
        items,
        template_column_count: columnCount,
        fill_width: false,
        with_background: knobs.with_background,
        row_class: 'row--equal-heights-content row--vertically-spaced',
      };

      knobs.rows = shouldRender(parentKnobs) ? CivicThemeGrid(itemsKnobs) : itemsKnobs;

      knobs.results_count = knobBoolean('With result count', true, parentKnobs.results_count, parentKnobs.knobTab) ? `Showing ${itemsCount} of ${resultNumber}` : null;
      knobs.rows_above = knobBoolean('With content above rows', true, parentKnobs.rows_above, parentKnobs.knobTab) ? Paragraph(new KnobValues({
        theme: knobs.theme,
        content: `Example content above rows ${randomText(randomInt(10, 75))}`,
        allow_html: true,
      })) : null;
      knobs.rows_below = knobBoolean('With content below rows', true, parentKnobs.rows_below, parentKnobs.knobTab) ? Paragraph(new KnobValues({
        theme: knobs.theme,
        content: `Example content below rows ${randomText(randomInt(10, 75))}`,
        allow_html: true,
      })) : null;
    }
  }

  return shouldRender(parentKnobs) ? CivicThemeList(knobs) : knobs;
};
