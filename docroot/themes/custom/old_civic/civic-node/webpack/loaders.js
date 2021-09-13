const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CSSLoader = {
  test: /\.css$/,
  exclude: /node_modules/,
  use: [
    {
      loader: MiniCssExtractPlugin.loader,
    },
    {
      loader: 'css-loader',
      options: {importLoaders: 1},
    },
  ],
};
module.exports = {
  CSSLoader: CSSLoader,
};
