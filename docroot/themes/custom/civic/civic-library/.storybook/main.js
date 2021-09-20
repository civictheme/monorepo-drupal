/**
 * Custom configuration for Storybook.
 *
 * Using Production version of the asset building Webpack configuration to
 * unify the building pipeline.
 */
const custom = require('./../webpack/webpack.prod.js');
const {merge} = require('webpack-merge');
const SpriteLoaderPlugin = require('svg-sprite-loader/plugin');

module.exports = {
  stories: [
    '../components/**/*.stories.js'
  ],
  addons: [
    '@storybook/addon-a11y',
    '@storybook/addon-essentials',
    '@storybook/addon-knobs',
    '@storybook/addon-links',
  ],
  webpackFinal: async (config) => {
    // Modify common configs to let Storybook take over.
    delete custom.output
    delete custom.plugins
    custom.plugins = [
      new SpriteLoaderPlugin({ plainSprite: true })
    ]
    // Special case: override whatever loader is used to load styles with a
    // style-loader in oder to have styles injected during the runtime.
    custom.module.rules[1].use[0] = 'style-loader';

    return merge(config, custom);
  }
}
