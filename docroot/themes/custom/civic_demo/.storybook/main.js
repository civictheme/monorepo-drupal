module.exports = {
  stories: [
    '../.storybook-components/**/*.stories.mdx',
    '../.storybook-components/**/*.stories.@(js|jsx|ts|tsx)',
  ],
  addons: [
    '@storybook/addon-a11y',
    '@storybook/addon-links',
    '@storybook/addon-essentials',
  ],
};
