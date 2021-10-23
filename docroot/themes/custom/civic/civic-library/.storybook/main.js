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
const backgroundUtils = require('../components/01-atoms/background/background.utils.js')
const path = require('path');

const customPlugin = new webpack.DefinePlugin({
  SCSS_VARIABLES: JSON.stringify(scssVariables.getVariables()),
  ICONS: JSON.stringify({
    icons: iconUtils.getIcons(),
    packs: iconUtils.getIconPacks()
  }),
  BACKGROUNDS: JSON.stringify(backgroundUtils.getBackgrounds()),
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
    'addon-screen-reader',
    'storybook-addon-pseudo-states',
  ],
  webpackFinal: async (config) => {
    // Add stories CSS.
    custom.entry.push(path.resolve(__dirname, 'css.stories.js'));

    // Modify common configs to let Storybook take over.
    delete custom.output
    custom.plugins = [
      customPlugin,
    ]
    // Special case: override whatever loader is used to load styles with a
    // style-loader in oder to have styles injected during the runtime.
    custom.module.rules[1].use[0] = 'style-loader';

    return merge(config, custom);
  }
}
