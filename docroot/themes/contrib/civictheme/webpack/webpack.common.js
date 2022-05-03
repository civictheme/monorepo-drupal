const path = require('path');
const glob = require('glob');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const magicImporter = require('node-sass-magic-importer');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
  entry: (function (pattern) {
    // Splitting entries into two chunks:
    // main: all styles used in components and drupal theme -> output: civictheme.css
    // ckeditor: nested styles used in ckeditor -> output: ckeditor.css
    const entries = {
      main: [],
      ckeditor: [],
    };

    // Scan for all JS.
    entries.main = glob.sync(pattern);
    // Add explicitly imported entries from components.
    entries.main.push(path.resolve(__dirname, 'components_css.js'));
    // Add explicitly imported entries from the current theme.
    entries.main.push(path.resolve(__dirname, 'theme_js.js'));
    entries.main.push(path.resolve(__dirname, 'theme_css.js'));
    entries.main.push(path.resolve(__dirname, 'assets.js'));

    // Add explicitly ckeditor.scss
    entries.ckeditor.push(path.resolve(__dirname, 'ckeditor_css.js'));

    return entries;
  }(path.resolve(__dirname, '../components/**/!(*.stories|*.component|*.min|*.test|*.script|*.utils).js'))),
  optimization: {
    splitChunks: {
      cacheGroups: {
        styles: {
          test: 'css/mini-extract',
          name: 'civictheme',
          chunks: (chunk) => (chunk.name === 'main'),
        },
        ckeditor: {
          test: 'css/mini-extract',
          name: 'ckeditor',
          chunks: (chunk) => (chunk.name === 'ckeditor'),
        },
      },
    },
  },
  output: {
    filename: (pathData) => (pathData.chunk.name === 'main' ? 'civictheme.js' : 'civictheme-[name].js'),
    path: path.resolve(__dirname, '../dist'),
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: ({ chunk }) => (chunk.name === 'main' ? 'civictheme.css' : `${chunk.name}.css`),
    }),
    new CleanWebpackPlugin(),
  ],
  module: {
    rules: [
      // JS Loader.
      {
        test: /^(?!.*\.(stories|component)\.js$).*\.js$/,
        exclude: /node_modules/,
        loader: 'babel-loader',
      },
      // CSS Loader.
      {
        test: /\.s[ac]ss$/i,
        exclude: /node_modules/,
        use: [
          MiniCssExtractPlugin.loader,
          {
            loader: 'css-loader',
            options: {
              sourceMap: true,
              url: false,
            },
          },
          {
            loader: 'sass-loader',
            options: {
              // Inject path to assets so that it does not have to be provided
              // in variables.base.scss
              additionalData: "$civictheme-assets-directory: '/themes/contrib/civictheme/dist/assets/';",
              sourceMap: true,
              sassOptions: {
                importer: magicImporter(),
              },
            },
          },
        ],
      },
      // Load all assets files to be available for distributions and Storybook.
      {
        test: /\.(jpe?g|png|svg|ico|woff|woff2|ttf|eot|webm|avi|mp4)$/,
        use: {
          loader: 'file-loader',
          options: {
            name: '[name].[ext]',
            // Preserve relative path.
            outputPath: (url, resourcePath, context) => path.relative(context, resourcePath),
          },
        },
      },
      // Twig loader.
      {
        test: /\.twig$/,
        use: [{
          loader: 'twigjs-loader',
        }],
      },
      // Wrap JS into Drupal.behaviours.
      {
        test: /components\/[^/]+\/(?!.*\.(stories|component|utils)\.js$).*\.js$/,
        exclude: /(node_modules|webpack|themejs\.js|css\.js)/,
        use: [{
          loader: 'babel-loader',
          options: {
            presets: [
              '@babel/preset-env',
            ],
            plugins: [
              './node_modules/babel-plugin-syntax-dynamic-import',
              './node_modules/babel-plugin-drupal-behaviors',
            ],
          },
        }],
      },
    ],
  },
  resolve: {
    alias: {
      '@base': path.resolve(__dirname, '../components/00-base'),
      '@atoms': path.resolve(__dirname, '../components/01-atoms'),
      '@molecules': path.resolve(__dirname, '../components/02-molecules'),
      '@organisms': path.resolve(__dirname, '../components/03-organisms'),
      '@templates': path.resolve(__dirname, '../components/04-templates'),
      '@pages': path.resolve(__dirname, '../components/05-pages'),
    },
  },
  stats: {
    errorDetails: true,
  },
};
