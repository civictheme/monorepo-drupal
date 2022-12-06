// phpcs:ignoreFile

import CivicThemeGrid from './grid.stories.twig';

export default {
  title: 'Base/Layout/Grid',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Grid = () => CivicThemeGrid();
