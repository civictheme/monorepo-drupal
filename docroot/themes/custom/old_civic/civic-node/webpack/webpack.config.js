const path = require('path');
const glob = require('glob');
const loaders = require('./loaders');
const plugins = require('./plugins');

module.exports = {
  entry: glob.sync('./components/**/*.css').reduce((entries, path) => {
    const entry = path.replace('/index.js', '');
    entries[entry] = path;
    return entries;
  }, {}),
  output: {
    path: path.resolve(__dirname, '../assets')
  },
  module: {
    rules: [
      loaders.CSSLoader,
    ]
  },
  plugins: [
    plugins.MiniCssExtractPlugin,
    plugins.FixStyleOnlyEntriesPlugin,
  ],
};
