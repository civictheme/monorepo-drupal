/**
 * Custom configuration for Storybook.
 *
 * Using Production version of the asset building Webpack configuration to
 * unify the building pipeline.
 */
const webpack = require('webpack')
const custom = require('./../webpack/webpack.prod.js');
const {merge} = require('webpack-merge');

const civic_variables = require('./civic-variables.js')

// Provide civic SCSS variables to stories via webpack.
const civicVariablesPlugin = new webpack.DefinePlugin({
  CIVIC_VARIABLES: JSON.stringify(civic_variables.getVariables())
})

module.exports = {
  stories: [
    '../components/**/*.stories.js'
  ],
  addons: [
    '@storybook/addon-knobs',
    '@storybook/addon-a11y',
    '@storybook/addon-essentials',
    '@storybook/addon-links',
  ],
  webpackFinal: async (config) => {
    // Modify common configs to let Storybook take over.
    delete custom.output
    delete custom.plugins
    custom.plugins = [
      civicVariablesPlugin
    ]
    // Special case: override whatever loader is used to load styles with a
    // style-loader in oder to have styles injected during the runtime.
    custom.module.rules[1].use[0] = 'style-loader';

    return merge(config, custom);
  }
}
