// phpcs:ignoreFile
import CivicThemeColors from './colors.stories.twig';
import { getThemes } from '../base.utils';

export default {
  title: 'Base/Colors',
};

function getColourMap(name) {
  const map = {};

  map.default = SCSS_VARIABLES[`ct-${name}-default`] || {};
  map.custom = SCSS_VARIABLES[`ct-${name}`];

  // Normalise colors as they may not be provided.
  if (!Object.prototype.hasOwnProperty.call(map.default, 'light') || !Object.prototype.hasOwnProperty.call(map.default, 'dark')) {
    map.default = {
      light: {},
      dark: {},
    };
  }

  if (!Object.prototype.hasOwnProperty.call(map.custom, 'light') || !Object.prototype.hasOwnProperty.call(map.custom, 'dark')) {
    map.custom = {
      light: {},
      dark: {},
    };
  }

  for (const theme in getThemes()) {
    map.custom[theme] = Object.keys(map.custom[theme]).filter((n) => Object.keys(map.default[theme]).indexOf(n) === -1)
      .reduce((obj2, key) => {
        if (key in map.custom[theme]) {
          obj2[key] = map.custom[theme][key];
        }
        return obj2;
      }, {});
  }

  return map;
}

export const Colors = () => {
  const sectionMap = {
    'Brand colors': {
      Standard: [
        'brand1',
        'brand2',
        'brand3',
      ],
    },
    'Palette colors': {
      Typography: [
        'heading',
        'body',
      ],
      Backgrounds: [
        'background-light',
        'background',
        'background-dark',
      ],
      Borders: [
        'border-light',
        'border',
        'border-dark',
      ],
      Interaction: [
        'interaction-text',
        'interaction-background',
        'interaction-hover-text',
        'interaction-hover-background',
        'interaction-focus',
      ],
      Highlight: [
        'highlight',
      ],
      Status: [
        'information',
        'warning',
        'error',
        'success',
      ],
      Custom: [],
    },
  };

  const brandMap = getColourMap('colors-brands');
  const paletteMap = getColourMap('colors');

  const colorMap = {
    'Brand colors': brandMap,
    'Palette colors': paletteMap,
  };

  const sections = {};

  for (const theme in getThemes()) {
    for (const sectionTitle in sectionMap) {
      for (const sectionName in sectionMap[sectionTitle]) {
        sections[theme] = sections[theme] || {};
        sections[theme][sectionTitle] = sections[theme][sectionTitle] || {};

        if (sectionName === 'Custom') {
          if (Object.keys(colorMap[sectionTitle].custom[theme]).length > 0) {
            sections[theme][sectionTitle][sectionName] = sections[theme][sectionTitle][sectionName] || {};
            sections[theme][sectionTitle][sectionName] = colorMap[sectionTitle].custom[theme];
          }
        } else {
          const colorNames = sectionMap[sectionTitle][sectionName];
          for (let i = 0; i < colorNames.length; i++) {
            sections[theme][sectionTitle][sectionName] = sections[theme][sectionTitle][sectionName] || {};
            sections[theme][sectionTitle][sectionName][colorNames[i]] = colorMap[sectionTitle].default[theme][colorNames[i]];
          }
        }
      }
    }
  }

  return CivicThemeColors({
    sections,
  });
};
