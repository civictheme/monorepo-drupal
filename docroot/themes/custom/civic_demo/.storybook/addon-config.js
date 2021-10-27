exports.default = function () {

  // Lean storybook config.
  let config = [
    '@storybook/addon-knobs',
    {
      name: '@storybook/addon-essentials',
      options: {
        controls: false,
        docs: false,
        actions: false,
      }
    },
    '@storybook/addon-links',
  ];

  // Accessibility, html and pseudo knobs.
  if (process.env.STORYBOOK_FULL === '1') {
    config = [
      ...config,
      ...[
        '@storybook/addon-a11y',
        '@whitespace/storybook-addon-html',
        'addon-screen-reader',
        'storybook-addon-pseudo-states',
      ]
    ]
  }

  return config;
}
