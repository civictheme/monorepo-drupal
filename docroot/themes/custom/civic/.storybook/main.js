const path = require('path');

module.exports = {
  addons: [
    '@storybook/addon-knobs'
  ],
  stories: [
    '../components/**/*.stories.@(js|mdx)'
  ],
  webpackFinal: async (config) => {
    // Add twig support.
    config.module.rules.unshift({
      test: /\.twig$/,
      use: [{
        loader: 'twigjs-loader'
      }]
    })

    // Override node_modules path to only use current theme's path.
    config.resolve.modules = [
      path.resolve(process.cwd(), 'node_modules')
    ]

    config.resolve.alias['templates'] = [
      path.resolve(__dirname, '../components/')
    ].join(':')

    // Provide aliases fot the current theme.
    config.resolve.alias = {
      ...config.resolve.alias,
      '@civic': path.resolve(__dirname, '../components/')
    }

    return config
  }
}
