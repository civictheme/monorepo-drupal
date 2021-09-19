var Twig = require('twig')
import '../components/00-base/normalize.scss';
import '../components/00-base/base.scss'

export const parameters = {
  backgrounds: {
    default: 'White',
    values: [
      {
        name: 'White',
        value: '#FFFFFF',
      },
      {
        name: 'Light',
        value: '#F2F4F5',
      },
      {
        name: 'Dark',
        value: '#002A39',
      },
    ],
  },
};
