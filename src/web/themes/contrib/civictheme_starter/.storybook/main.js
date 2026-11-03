// phpcs:ignoreFile
import { createRequire } from 'node:module';
import path from 'node:path';

const require = createRequire(import.meta.url);

// The uikit colours story imports the tokens dist through a repo-relative
// path that only exists in the uikit monorepo. Point it at the copy shipped
// inside the @civictheme/uikit package.
const uikitDir = path.dirname(require.resolve('@civictheme/uikit/package.json'));
const tokensDistAlias = {
  find: /^(\.\.\/)+tokens\/dist\/(resolved\.(?:light|dark)\.json)$/,
  replacement: `${uikitDir}/packages/tokens/dist/$2`,
};

const config = {
  stories: [
    '../components/**/*.stories.js',
  ],
  addons: [
    '@storybook/addon-links',
    '@whitespace/storybook-addon-html',
    '@storybook/addon-docs',
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
