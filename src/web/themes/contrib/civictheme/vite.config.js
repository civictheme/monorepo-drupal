import { defineConfig } from 'vite';
import { basename, resolve } from 'path';
import { globSync } from 'node:fs';
import twig from 'vite-plugin-twig-drupal';
import sdcPlugin from './.storybook/sdc-plugin.js';

const componentDir = resolve(import.meta.dirname, './components');
// The SDC namespace is the theme machine name: the uikit-install rewrite
// stamps it over the component import, so resolve it the same way (from the
// info.yml filename) instead of hardcoding the current theme name.
const infoFile = globSync('*.info.yml', { cwd: import.meta.dirname }).sort().shift();
const machineName = infoFile ? basename(infoFile, '.info.yml') : basename(import.meta.dirname);

export default defineConfig(({ mode }) => ({
  plugins: [
    twig({
      namespaces: {
        [machineName]: componentDir,
      },
    }),
    sdcPlugin({ path: componentDir, namespaces: [machineName] }),
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
