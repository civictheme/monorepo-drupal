const path = require('path');
const glob = require('glob');
const {CleanWebpackPlugin} = require('clean-webpack-plugin');
const globImporter = require('node-sass-glob-importer');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const SpriteLoaderPlugin = require('svg-sprite-loader/plugin');

module.exports = {
  entry: function (pattern) {
    // Scan for all JS.
    let entries = glob.sync(pattern);
    // Add explicitly imported entries from components.
    entries.push(path.resolve(__dirname, 'components_css.js'));
    entries.push(path.resolve(__dirname, 'components_svg.js'));
    // Add explicitly imported entries from the current theme.
    entries.push(path.resolve(__dirname, 'theme_js.js'));
    entries.push(path.resolve(__dirname, 'theme_css.js'));
    entries.push(path.resolve(__dirname, 'theme_svg.js'));
    return entries;
  }(path.resolve(__dirname, '../combined-components/**/!(*.stories|*.component|*.min|*.test).js')),
  output: {
    filename: 'civic.js',
    path: path.resolve(__dirname, '../dist'),
  },
  plugins: [
    new SpriteLoaderPlugin({
      plainSprite: true,
    }),
    new MiniCssExtractPlugin({
      filename: '../dist/civic.css',
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
              sourceMap: true,
              sassOptions: {
                importer: globImporter(),
              },
            },
          },
        ],
      },
      // SVG Sprite Loader.
      {
        test: /icons\/.*\.svg$/,
        loader: 'svg-sprite-loader',
        options: {
          extract: true,
          spriteFilename: (name) => {
            // Export as multiple collections grouped by the parent directory.
            return `icons/civic-${/icons([\\|/])(.*?)\1/gm.exec(name)[2].toLowerCase().replace(' ', '-').replace(/[^a-z0-9\-]+/, '')}.svg`;
          },
          symbolId: filePath => {
            // Set symbol id to '<group>-<name>'.
            let paths = filePath.split('/');
            const name = paths.pop();
            const prefix = paths.pop();
            return [prefix, name].join('-').toLowerCase().replace('.svg', '').replace(' ', '-').replace(/[^a-z0-9\-]+/, '');
          }
        },
      },
      // Twig loader.
      {
        test: /\.twig$/,
        use: [{
          loader: 'twigjs-loader'
        }]
      },
      // Wrap JS into Drupal.behaviours.
      {
        test: /components\/[^\/]+\/(?!.*\.(stories|component)\.js$).*\.js$/,
        exclude: /(node_modules|webpack|themejs\.js|css\.js)/,
        use: [{
          loader: 'babel-loader',
          options: {
            presets:[
              '@babel/preset-env'
            ],
            plugins: [
              './node_modules/babel-plugin-syntax-dynamic-import',
              './node_modules/babel-plugin-drupal-behaviors',
            ],
          }
        }]
      }
    ],
  },
  resolve: {
    alias: {
      '@base': path.resolve(__dirname, '../combined-components/00-base'),
      '@atoms': path.resolve(__dirname, '../combined-components/01-atoms'),
      '@molecules': path.resolve(__dirname, '../combined-components/02-molecules'),
      '@organisms': path.resolve(__dirname, '../combined-components/03-organisms'),
      '@templates': path.resolve(__dirname, '../combined-components/04-templates'),
      '@pages': path.resolve(__dirname, '../combined-components/05-pages'),
    }
  },
  stats: {
    errorDetails: true,
  },
};
