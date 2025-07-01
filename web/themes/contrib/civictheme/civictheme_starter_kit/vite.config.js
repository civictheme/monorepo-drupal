import { defineConfig } from 'vite';
import { resolve, basename } from 'path';
import twig from 'vite-plugin-twig-drupal';
import sdcPlugin from './.storybook/sdc-plugin.js';
const subthemeName = basename(import.meta.dirname);
const componentDir = resolve(import.meta.dirname, './components_combined');
const coreComponentDir = resolve(import.meta.dirname, './.components-civictheme');

export default defineConfig(({ mode }) => ({
  plugins: [
    twig({
      namespaces: {
        civictheme: componentDir,
        [subthemeName]: componentDir,
      },
    }),
    sdcPlugin({
      path: componentDir,
      namespaces: ['civictheme', subthemeName],
    }),
    // This plugin allow watching files in the ./dist folder.
    {
      name: 'watch-dist',
      configureServer: (server) => {
        server.watcher.options = {
          ...server.watcher.options,
          ignored: [
            '**/.git/**',
            '**/node_modules/**',
            '**/.logs/**',
          ]
        }
      }
    }
  ],
}));
