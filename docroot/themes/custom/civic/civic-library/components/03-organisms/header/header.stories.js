import { boolean, radios, text } from '@storybook/addon-knobs';
import { Logo } from '../../01-atoms/logo/logo.stories';
import { getSlots } from '../../00-base/base.stories';
import CivicHeader from './header.stories.twig';

import './header.scss';
import './header.stories.scss';
import '../menu/menu';

export default {
  title: 'Organisms/Header',
  parameters: {
    layout: 'fullscreen',
  },
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
    showTop: boolean('Show top navigation', true, generalKnobTab),
    slogan: text('Site slogan', 'Visually engaging digital experiences', generalKnobTab),
    showBottom: boolean('Show bottom navigation', true, generalKnobTab),
  };

  return CivicHeader({
    ...generalKnobs,
    ...getSlots([
      'top',
      'logo',
      'content',
      'bottom',
    ]),
    logo: Logo,
  });
};
