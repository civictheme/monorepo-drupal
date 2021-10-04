import { radios } from '@storybook/addon-knobs';
import { getSlots } from '../../00-base/base.stories';
import CivicHeader from './header.twig';
import { Logo } from '../../01-atoms/logo/logo.stories';

import './header.scss';

export default {
  title: 'Organisms/Header',
};

export const Header = () => {
  const generalKnobTab = 'General';

  const generalKnobs = {
    theme: radios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      generalKnobTab,
    ),
  };

  return CivicHeader({
    ...generalKnobs,
    logo: Logo,
    ...getSlots([
      'top',
      'logo',
      'content',
      'bottom',
    ]),
  });
};
