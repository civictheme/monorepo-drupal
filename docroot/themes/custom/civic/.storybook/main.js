/**
 * Custom configuration for Storybook.
 */

// Using Production version of the asset building Webpack configuration to
// unify the building pipeline.
const custom = require('./../webpack/webpack.prod.js');
const {merge} = require('webpack-merge');
const webpack = require('webpack')
const scssVariables = require('./importer.scss_variables.js')
const iconUtils = require('../components/01-atoms/icon/icon.utils.js')

const customPlugin = new webpack.DefinePlugin({
  SCSS_VARIABLES: JSON.stringify(scssVariables.getVariables()),
  ICONS: JSON.stringify({
    icons: iconUtils.getIcons(),
    packs: iconUtils.getIconPacks()
  })
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
    '@whitespace/storybook-addon-html',
  ],
  webpackFinal: async (config) => {
    // Remove theme-related entries as components should not have them.
    custom.entry = custom.entry.filter(path => !/theme_/g.test(path));

    // Modify common configs to let Storybook take over.
    delete custom.output
    delete custom.plugins
    custom.plugins = [
      customPlugin,
    ]
    // Special case: override whatever loader is used to load styles with a
    // style-loader in oder to have styles injected during the runtime.
    custom.module.rules[1].use[0] = 'style-loader';

    return merge(config, custom);
  }
}
