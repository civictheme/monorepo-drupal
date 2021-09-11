const path = require('path');

module.exports = {
  addons: [
    '@storybook/addon-knobs'
  ],
  // Combine stories from the parent and current theme.
  stories: [
    '../../civic/components/**/*.stories.@(js|mdx)',
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

    // Combine components from the parent and current themes.
    config.resolve.alias['templates'] = [
      path.resolve(__dirname, '../../civic/components/'),
      path.resolve(__dirname, '../components/')
    ].join(':')

    // Provide aliases for the parent and current themes.
    config.resolve.alias = {
      ...config.resolve.alias,
      '@civic': path.resolve(__dirname, '../../civic/components/'),
      '@custom': path.resolve(__dirname, '../components/')
    }

    return config
  }
}
