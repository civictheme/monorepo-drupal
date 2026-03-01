// phpcs:ignoreFile
/**
 * CivicTheme List component stories.
 */

import Component from './list.twig';
import ListData from './list.stories.data';

const meta = {
  title: 'Organisms/List',
  component: Component,
  argTypes: {
    title: {
      control: { type: 'text' },
    },
    link_above: {
      control: { type: 'object' },
    },
    filters: {
      control: { type: 'text' },
    },
    results_count: {
      control: { type: 'text' },
    },
    rows_above: {
      control: { type: 'text' },
    },
    rows: {
      control: { type: 'text' },
    },
    rows_below: {
      control: { type: 'text' },
    },
    empty: {
      control: { type: 'text' },
    },
    pagination: {
      control: { type: 'text' },
    },
    footer: {
      control: { type: 'text' },
    },
    link_below: {
      control: { type: 'object' },
    },
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    vertical_spacing: {
      control: { type: 'radio' },
      options: ['none', 'top', 'bottom', 'both'],
    },
    with_background: {
      control: { type: 'boolean' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const List = {
  parameters: {
    layout: 'padded',
  },
  args: ListData.args('light'),
};

export const ListDark = {
  parameters: {
    layout: 'padded',
  },

  args: ListData.args('dark'),

  globals: {
    backgrounds: {
      value: 'dark',
    },
  },
};

export const ListGroupFilters = {
  parameters: {
    layout: 'padded',
  },
  args: ListData.args('light', { group: true }),
};

export const ListNoResults = {
  parameters: {
    layout: 'padded',
  },
  args: ListData.args('light', { items: [] }),
};

export const ListGroupFiltersWithSelectedFilters = {
  parameters: {
    layout: 'padded',
  },
  args: ListData.args('light', { group: true, selectedFilters: true }),
};

export const ListNavigationCard = {
  parameters: {
    layout: 'padded',
  },
  args: ListData.args('light', { component: 'navigation', columnCount: 1 }),
};

export const ListSnippet = {
  parameters: {
    layout: 'padded',
  },
  args: ListData.args('light', { component: 'snippet', columnCount: 1 }),
};

export const ListPromoCardEqualHeights = {
  parameters: {
    layout: 'padded',
  },
  args: ListData.args('light', {
    component: 'promo',
    items: [
      {
        title: 'Duis in nulla.',
        tags: [
          'Tag small text',
          'Tag with large text that spans over two lines',
        ],
      },
      {
        title: 'Veniam occaecat deserunt sint dolor minim dolore occaecat.',
        tags: [
          'Tag with large text that spans over two lines',
          'Tag small text',
        ],
      },
      { title: 'Do anim occaecat dolor cupidatat est eu sunt labore non aute nisi proident ullamco pariatur ut ex pariatur.' },
    ],
  }, ListData.basicOverrides({ title: 'Promo Card Equal Height' })),
};

export const ListEventCardEqualHeights = {
  parameters: {
    layout: 'padded',
  },
  args: ListData.args('light', {
    component: 'event',
    items: [
      { title: 'Duis in nulla.' },
      { title: 'Veniam occaecat deserunt sint dolor minim dolore occaecat.' },
      { title: 'Do anim occaecat dolor cupidatat est eu sunt labore non aute nisi proident ullamco pariatur ut ex pariatur.' },
    ],
  }, ListData.basicOverrides({ title: 'Event Card Equal Height' })),
};

export const ListNavigationEqualHeights = {
  parameters: {
    layout: 'padded',
  },
  args: ListData.args('light', {
    component: 'navigation',
    items: [
      { title: 'Duis in nulla.' },
      { title: 'Veniam occaecat deserunt sint dolor minim dolore occaecat.' },
      { title: 'Do anim occaecat dolor cupidatat est eu sunt labore non aute nisi proident ullamco pariatur ut ex pariatur.' },
    ],
  }, ListData.basicOverrides({ title: 'Navigation Card Equal Height' })),
};

export const ListPromoCardTwoColumn = {
  parameters: {
    layout: 'padded',
  },
  args: ListData.args('light', {
    component: 'promo',
    items: [
      { title: 'Duis in nulla.' },
      { title: 'Veniam occaecat deserunt sint dolor minim dolore occaecat.' },
      { title: 'Do anim occaecat dolor cupidatat est eu sunt labore non aute nisi proident ullamco pariatur ut ex pariatur.' },
      { title: 'Proident ad mollit aute quis laborum duis laborum exercitation.' },
    ],
    columnCount: 2,
  }, ListData.basicOverrides({ title: 'Promo Card Two Column' })),
};

export const ListPromoCardFourColumn = {
  parameters: {
    layout: 'padded',
  },
  args: ListData.args('light', {
    component: 'promo',
    items: [
      { title: 'Duis in nulla.' },
      { title: 'Veniam occaecat deserunt sint dolor minim dolore occaecat.' },
      { title: 'Do anim occaecat dolor cupidatat est eu sunt labore non aute nisi proident ullamco pariatur ut ex pariatur.' },
      { title: 'Proident ad mollit aute quis laborum duis laborum exercitation.' },
    ],
    columnCount: 4,
  }, ListData.basicOverrides({ title: 'Promo Card Four Column' })),
};

export const ListPromoCardAutoColumn = {
  parameters: {
    layout: 'padded',
  },
  args: ListData.args('light', {
    component: 'promo',
    items: [
      { title: 'Cupidatat ad dolor in est.' },
      { title: 'Labore incididunt.' },
      { title: 'Consectetur sit ut eu.' },
      { title: 'Lorem ipsum eiusmod ex dolor.' },
      { title: 'Id do elit.' },
      { title: 'Cillum aute nostrud eiusmod.' },
    ],
    columnCount: 6,
    columnAutoBreakpoint: true,
  }, ListData.basicOverrides({ title: 'Promo Card Auto Column' })),
};
