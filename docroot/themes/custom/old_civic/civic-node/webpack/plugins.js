const path = require('path');
const _MiniCssExtractPlugin = require('mini-css-extract-plugin');
const _FixStyleOnlyEntriesPlugin = require("webpack-fix-style-only-entries");

const MiniCssExtractPlugin = new _MiniCssExtractPlugin({
  filename: "[name]",
  chunkFilename: "[name]"
});

const FixStyleOnlyEntriesPlugin = new _FixStyleOnlyEntriesPlugin();

module.exports = {
  MiniCssExtractPlugin: MiniCssExtractPlugin,
  FixStyleOnlyEntriesPlugin: FixStyleOnlyEntriesPlugin,
};
