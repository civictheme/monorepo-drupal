/**
 * Custom configuration for Storybook.
 */

// Using Production version of the asset building Webpack configuration to
// unify the building pipeline.
const custom = require('./../webpack/webpack.prod.js');
const {merge} = require('webpack-merge');
const webpack = require('webpack')
const civicVariables = require('./civic-variables.js')
const civicIcons = require('./civic-icons.js')

module.exports = {
  stories: [
    '../combined-components/**/*.stories.js',
  ],
  addons: [
    '@storybook/addon-knobs',
    '@storybook/addon-a11y',
    '@storybook/addon-essentials',
    '@storybook/addon-links',
    '@whitespace/storybook-addon-html',
  ],
  webpackFinal: async (config) => {
    // Remove theme-related entries as components should not have them.
    custom.entry = custom.entry.filter(path => !/theme_/g.test(path));

    // Modify common configs to let Storybook take over.
    delete custom.output
    custom.plugins = [
      // Provide Civic SCSS variables to stories via webpack.
      new webpack.DefinePlugin({
        CIVIC_VARIABLES: JSON.stringify(civicVariables.getVariables()),
        CIVIC_ICONS: JSON.stringify(civicIcons.getIcons())
      })
    ]
    // Special case: override whatever loader is used to load styles with a
    // style-loader in oder to have styles injected during the runtime.
    custom.module.rules[1].use[0] = 'style-loader';

    return merge(config, custom);
  }
}
