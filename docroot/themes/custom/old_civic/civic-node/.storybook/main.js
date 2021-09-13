const path = require('path');

module.exports = {
  stories: [
    '../components/**/*.stories.@(js|mdx)'
  ],
  addons: [
    '@storybook/addon-knobs'
  ],
  webpackFinal: async (config) => {
    // Add twig support
    config.module.rules.unshift({
      test: /\.twig$/,
      use: [{
        loader: 'twigjs-loader'
      }]
    })

    // Tell Storybook where your components live
    config.resolve.alias['templates'] = path.resolve(
      __dirname,
      '../components/'
    )

    return config
  }
}
