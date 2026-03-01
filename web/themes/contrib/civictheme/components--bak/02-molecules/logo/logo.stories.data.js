// phpcs:ignoreFile
import Constants from '../../../dist/constants.json'; // eslint-disable-line import/no-unresolved

export default {
  args: (theme = 'light') => ({
    theme,
    type: 'default',
    logos: {
      primary: {
        mobile: {
          url: Constants.LOGOS[theme].primary.mobile,
          alt: 'Primary logo mobile alt text',
        },
        desktop: {
          url: Constants.LOGOS[theme].primary.desktop,
          alt: 'Primary logo desktop alt text',
        },
      },
      secondary: {
        mobile: {
          url: Constants.LOGOS[theme].secondary.mobile,
          alt: 'Secondary logo mobile alt text',
        },
        desktop: {
          url: Constants.LOGOS[theme].secondary.desktop,
          alt: 'Secondary logo desktop alt text',
        },
      },
    },
    url: 'https://example.com',
    title: 'Logo title',
    attributes: null,
    modifier_class: '',
  }),
};
