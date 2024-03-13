// phpcs:ignoreFile

const path = require('path');
const glob = require('glob');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const magicImporter = require('node-sass-magic-importer');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
  entry: (function (pattern) {
    // Splitting entries into three chunks:
    // main: all styles used in components and drupal theme -> output: styles.css
    // variables: CSS variables -> output: styles.variables.css
    // editor: nested styles used in editor -> output: styles.editor.css
    const entries = {
      main: [],
      variables: [],
      editor: [],
    };

    // Scan for all JS.
    entries.main = glob.sync(pattern);
    // Add explicitly imported entries from components.
    entries.main.push(path.resolve(__dirname, 'components_css.js'));
    // Add explicitly imported entries from the current theme.
    entries.main.push(path.resolve(__dirname, 'theme_js.js'));
    entries.main.push(path.resolve(__dirname, 'theme_css.js'));
    entries.main.push(path.resolve(__dirname, 'assets.js'));

    // Add libraries.
    entries.main.push(path.resolve(__dirname, 'libraries.js'));

    // Add explicitly css_variables.js.
    entries.variables.push(path.resolve(__dirname, 'css_variables.js'));

    // Add explicitly editor.scss
    entries.editor.push(path.resolve(__dirname, 'editor_css.js'));

    return entries;
  }(path.resolve(__dirname, '../components_combined/**/!(*.stories|*.component|*.min|*.test|*.script|*.utils).js'))),
  optimization: {
    splitChunks: {
      cacheGroups: {
        styles: {
          test: 'css/mini-extract',
          name: 'styles',
          chunks: (chunk) => (chunk.name === 'main'),
        },
        variables: {
          test: 'css/mini-extract',
          name: 'variables',
          chunks: (chunk) => (chunk.name === 'variables'),
        },
        editor: {
          test: 'css/mini-extract',
          name: 'editor',
          chunks: (chunk) => (chunk.name === 'editor'),
        },
      },
    },
  },
  output: {
    filename: (pathData) => (pathData.chunk.name === 'main' ? 'scripts.js' : 'scripts-[name].js'),
    path: path.resolve(__dirname, '../dist'),
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: ({ chunk }) => (chunk.name === 'main' ? 'styles.css' : `styles.${chunk.name}.css`),
    }),
    new CleanWebpackPlugin({
      dry: false,
      dangerouslyAllowCleanPatternsOutsideProject: true,
      cleanAfterEveryBuildPatterns: [
        '../dist/scripts-variables.js',
        '../dist/scripts-variables.js.map',
        '../dist/scripts-editor.js',
        '../dist/scripts-editor.js.map',
      ],
    }),
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
              additionalData: `$ct-assets-directory: '/themes/custom/civictheme_starter_kit/dist/assets/';`,
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
        test: /components_combined\/[^/]+\/(?!.*\.(stories|component|utils)\.js$).*\.js$/,
        exclude: /(node_modules|webpack|themejs\.js|css\.js)/,
        use: [{
          loader: 'babel-loader',
          options: {
            presets: [
              '@babel/preset-env',
            ],
            plugins: [
              './node_modules/babel-plugin-syntax-dynamic-import',
              './webpack/babel-plugin-drupal-behavior-wrapper.js',
            ],
          },
        }],
      },
    ],
  },
  resolve: {
    alias: {
      '@base': path.resolve(__dirname, '../components_combined/00-base'),
      '@atoms': path.resolve(__dirname, '../components_combined/01-atoms'),
      '@molecules': path.resolve(__dirname, '../components_combined/02-molecules'),
      '@organisms': path.resolve(__dirname, '../components_combined/03-organisms'),
      '@templates': path.resolve(__dirname, '../components_combined/04-templates'),
      '@ct-base': path.resolve(__dirname, '../.components-civictheme/00-base'),
      '@ct-atoms': path.resolve(__dirname, '../.components-civictheme/01-atoms'),
      '@ct-molecules': path.resolve(__dirname, '../.components-civictheme/02-molecules'),
      '@ct-organisms': path.resolve(__dirname, '../.components-civictheme/03-organisms'),
      '@ct-templates': path.resolve(__dirname, '../.components-civictheme/04-templates'),
    },
  },
  stats: {
    errorDetails: true,
  },
};
