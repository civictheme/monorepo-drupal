module.exports = {
  stories: [
    '../../civic_emulsify/components/**/*.stories.mdx',
    '../../civic_emulsify/components/**/*.stories.@(js|jsx|ts|tsx)',
    '../components/**/*.stories.mdx',
    '../components/**/*.stories.@(js|jsx|ts|tsx)',
  ],
  addons: [
    '@storybook/addon-a11y',
    '@storybook/addon-links',
    '@storybook/addon-essentials',
  ],
};
