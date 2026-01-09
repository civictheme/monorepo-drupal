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
};

export default config;
