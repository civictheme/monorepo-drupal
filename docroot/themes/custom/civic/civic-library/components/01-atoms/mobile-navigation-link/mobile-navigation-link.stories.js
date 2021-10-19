import {
  boolean, radios, select, text,
} from '@storybook/addon-knobs';

import CivicMobileNavigationLink from './mobile-navigation-link.twig';
import './mobile-navigation-link.scss';

export default {
  title: 'Atom/Mobile Navigation Link',
  parameters: {
    layout: 'centered',
  },
};

export const MobileNavigationLink = () => {
  const { icons } = ICONS;
  const sizes = SCSS_VARIABLES['civic-icon-sizes'];
  const defaultIcon = icons.indexOf('arrows_rightarrow_3');
  return CivicMobileNavigationLink({
    theme: radios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
    ),
    icon: select('Icon', icons, defaultIcon !== -1 ? icons[defaultIcon] : icons[0]),
    size: radios('Size', sizes, sizes[0]),
    text: text('Text', 'Mobile navigation link'),
    url: text('URL', 'http://example.com'),
    modifier_class: text('Additional class', ''),
  });
};
