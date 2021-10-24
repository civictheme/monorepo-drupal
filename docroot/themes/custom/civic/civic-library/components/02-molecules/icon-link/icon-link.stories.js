import {
  boolean, radios, select, text,
} from '@storybook/addon-knobs';

import CivicIconLink from './icon-link.twig';

export default {
  title: 'Molecules/Icon Link',
  parameters: {
    layout: 'centered',
  },
};

export const IconLink = () => {
  const { icons } = ICONS;
  const sizes = SCSS_VARIABLES['civic-icon-sizes'];
  const defaultIcon = icons.indexOf('brands_facebook');
  return CivicIconLink({
    theme: radios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
    ),
    symbol: select('Symbol', icons, defaultIcon !== -1 ? icons[defaultIcon] : icons[0]),
    size: radios('Size', sizes, sizes[0]),
    text: text('Text', 'Go to service'),
    url: text('URL', 'http://example.com'),
    with_border: boolean('Add border', true),
    new_window: boolean('Open in a new window', false),
    modifier_class: text('Additional class', ''),
  });
};
