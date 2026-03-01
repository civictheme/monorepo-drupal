// phpcs:ignoreFile
import Component from './colors.stories.twig';
import Constants from '../../../dist/constants.json'; // eslint-disable-line import/no-unresolved

const themes = {
  light: 'Light',
  dark: 'Dark',
};

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

function getColorMap(name) {
  const map = {};

  map.default = Constants.SCSS_VARIABLES[`ct-${name}-default`] || {};
  map.custom = Constants.SCSS_VARIABLES[`ct-${name}`];

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

  for (const theme in themes) {
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

const brandMap = getColorMap('colors-brands');
const paletteMap = getColorMap('colors');

const colorMap = {
  'Brand colors': brandMap,
  'Palette colors': paletteMap,
};

const sections = {};

for (const theme in themes) {
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

const meta = {
  title: 'Base/Colors',
  component: Component,
  argTypes: {
    sections: {
      table: {
        disable: true,
      },
    },
  },
};

export default meta;

export const Colors = {
  parameters: {
    layout: 'fullscreen',
    html: {
      disable: true,
    },
  },
  args: {
    sections,
  },
};
