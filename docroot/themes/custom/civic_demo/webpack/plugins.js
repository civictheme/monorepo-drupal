/* eslint-disable no-underscore-dangle */
const webpack = require('webpack');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const _MiniCssExtractPlugin = require('mini-css-extract-plugin');


const MiniCssExtractPlugin = new _MiniCssExtractPlugin({
  filename: 'style.css',
  chunkFilename: '[id].css',
});

const ProgressPlugin = new webpack.ProgressPlugin();

module.exports = {
  ProgressPlugin,
  MiniCssExtractPlugin,
  CleanWebpackPlugin: new CleanWebpackPlugin({
    cleanOnceBeforeBuildPatterns: ['!*.{png,jpg,gif,svg}'],
    cleanAfterEveryBuildPatterns: ['remove/**', '!js', '!*.{png,jpg,gif,svg}'],
  }),
};
