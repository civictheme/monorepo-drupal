const path = require('path');

module.exports = {
  stories: [
    '../components/**/*.stories.js'
  ],
  addons: [
    '@storybook/addon-a11y',
    '@storybook/addon-essentials',
    '@storybook/addon-knobs',
    '@storybook/addon-links',
    // SCSS is compiled using SCSS loader preset provided by the package below.
    '@storybook/preset-scss'
  ],
  webpackFinal: async (config) => {
    // Add twig support.
    config.module.rules.unshift({
      test: /\.twig$/,
      use: [{
        loader: 'twigjs-loader'
      }]
    })

    config.resolve.alias['templates'] = path.resolve(
      __dirname,
      '../components/'
    )

    return config
  }
}
