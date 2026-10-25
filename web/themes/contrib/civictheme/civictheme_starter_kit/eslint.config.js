// phpcs:ignoreFile
import js from '@eslint/js';
import globals from 'globals';
import importNewlines from 'eslint-plugin-import-newlines';

const customGlobals = {
  BACKGROUNDS: 'readonly',
  Drupal: 'readonly',
  ICONS: 'readonly',
  LOGOS: 'readonly',
  SCSS_VARIABLES: 'readonly',
  Set: 'readonly',
  dom: 'readonly',
  assertUniqueCssClasses: 'readonly',
  global: 'readonly',
  __dirname: 'readonly',
  __filename: 'readonly',
};

export default [
  {
    ignores: [
      '**/node_modules/**',
      '**/vendor/**',
      '**/storybook-static/**',
      '**/dist/**',
      '**/*.min.js',
      '**/*.helpers.js',
      '**/*.utils.js',
      '**/collapsible.test.js',
    ],
  },

  js.configs.recommended,

  {
    linterOptions: {
      reportUnusedDisableDirectives: 'off',
    },
    plugins: {
      'import-newlines': importNewlines,
    },
    languageOptions: {
      ecmaVersion: 'latest',
      sourceType: 'module',
      globals: {
        ...globals.browser,
        ...globals.es2021,
        ...globals.jest,
        ...customGlobals,
      },
    },
    rules: {
      'func-names': 'off',
      'guard-for-in': 'off',
      'max-len': ['error', { code: 10000, comments: 80 }],
      'no-continue': 'off',
      'no-nested-ternary': 'off',
      'no-new': 'off',
      'no-param-reassign': 'off',
      'no-plusplus': 'off',
      'no-restricted-syntax': 'off',
      'object-curly-newline': ['error', { ImportDeclaration: 'never' }],
      quotes: ['error', 'single', { avoidEscape: true, allowTemplateLiterals: true }],
      strict: 'off',
      'import-newlines/enforce': ['error', { items: 100, forceSingleLine: true, 'max-len': 10000 }],
      'no-useless-assignment': 'off',
      'no-unused-vars': ['error', { args: 'none', caughtErrors: 'none' }],
      'no-bitwise': 'error',
      'no-console': 'error',
      'no-use-before-define': 'error',
      'no-underscore-dangle': 'error',
      'no-caller': 'error',
      'no-alert': 'error',
      'prefer-template': 'error',
      'new-cap': ['error', { capIsNew: false }],
    },
  },
];
