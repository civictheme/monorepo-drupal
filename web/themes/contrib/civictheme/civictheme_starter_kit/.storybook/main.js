// phpcs:ignoreFile
import path from 'node:path';
import { fileURLToPath } from 'node:url';

// The uikit colours story (distributed through components_combined) imports
// the tokens dist through a repo-relative path that only exists in the uikit
// monorepo. Point it at the copies the parent CivicTheme theme ships with its
// components.
const combinedDir = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '../components_combined');
const tokensDistAlias = {
  find: /^(\.\.\/)+tokens\/dist\/(resolved\.(?:light|dark)\.json)$/,
  replacement: `${combinedDir}/$2`,
};

const config = {
  stories: [
    '../components_combined/**/*.stories.js',
  ],
  addons: [
    '@storybook/addon-links',
    '@whitespace/storybook-addon-html',
    '@storybook/addon-docs'
  ],
  framework: {
    name: '@storybook/html-vite',
    options: {},
  },
  staticDirs: [{ from: '../dist/assets', to: '/assets' }, './static'],
  async viteFinal(viteConfig) {
    viteConfig.resolve = viteConfig.resolve ?? {};
    const alias = viteConfig.resolve.alias;
    viteConfig.resolve.alias = Array.isArray(alias)
      ? [...alias, tokensDistAlias]
      : [
        ...Object.entries(alias ?? {}).map(([find, replacement]) => ({ find, replacement })),
        tokensDistAlias,
      ];
    return viteConfig;
  },
};

export default config;
