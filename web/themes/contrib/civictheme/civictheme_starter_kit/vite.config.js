import { defineConfig } from 'vite';
import { resolve } from 'path';
import twig from 'vite-plugin-twig-drupal';
import sdcPlugin from './.storybook/sdc-plugin.js';
const componentDir = resolve(import.meta.dirname, './components_combined');
const coreComponentDir = resolve(import.meta.dirname, './.components-civictheme');

export default defineConfig(({ mode }) => ({
  plugins: [
    twig({
      namespaces: {
        civictheme: componentDir,
      },
    }),
    sdcPlugin({ path: componentDir }),
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
