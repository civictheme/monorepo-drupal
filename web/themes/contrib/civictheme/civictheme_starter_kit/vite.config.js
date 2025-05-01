import { defineConfig } from 'vite';
import { resolve, parse } from 'path';
import twig from 'vite-plugin-twig-drupal';
import sdcPlugin from './.storybook/sdc-plugin.js';

const componentDir = resolve(import.meta.dirname, './components_combined');
const coreComponentDir = resolve(import.meta.dirname, './.components-civictheme');

export default defineConfig(({ mode }) => ({
  plugins: [
    twig({
      namespaces: {
        'base': resolve(componentDir, './00-base'),
        'atoms': resolve(componentDir, './01-atoms'),
        'molecules': resolve(componentDir, './02-molecules'),
        'organisms': resolve(componentDir, './03-organisms'),
        'templates': resolve(componentDir, './04-templates'),
        'ct-base': resolve(coreComponentDir, './00-base'),
        'ct-atoms': resolve(coreComponentDir, './01-atoms'),
        'ct-molecules': resolve(coreComponentDir, './02-molecules'),
        'ct-organisms': resolve(coreComponentDir, './03-organisms'),
        'ct-templates': resolve(coreComponentDir, './04-templates'),
        civictheme: componentDir,
        [parse(import.meta.dirname).name]: componentDir
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
