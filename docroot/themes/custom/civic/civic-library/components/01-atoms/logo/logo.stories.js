import { radios, text } from '@storybook/addon-knobs';

import CivicLogo from './logo.twig';
import logoDesktopLight from '../../../assets/logo-desktop-light.png';
import logoDesktopDark from '../../../assets/logo-desktop-dark.png';
import logoMobileLight from '../../../assets/logo-mobile-light.png';
import logoMobileDark from '../../../assets/logo-mobile-dark.png';

import './logo.scss';

export default {
  title: 'Atom/Logo',
  parameters: {
    layout: 'centered',
  },
};

export const Logo = () => {
  const generalKnobTab = 'General';

  const generalKnobs = {
    theme: radios('Theme', {
      Light: 'light',
      Dark: 'dark',
    }, 'light', generalKnobTab),
  };

  generalKnobs.logos = {
    mobile: {
      src: generalKnobs.theme === 'light' ? logoMobileDark : logoMobileLight,
      alt: 'Logo mobile alt text',
    },
    desktop: {
      src: generalKnobs.theme === 'light' ? logoDesktopDark : logoDesktopLight,
      alt: 'Logo desktop alt text',
    },
  };

  generalKnobs.modifier_class = text('Additional class', '', generalKnobTab);

  const html = CivicLogo({
    ...generalKnobs,
  });

  return `<div class="civic-logo-example story-wrapper-size--small">${html}</div>`;
};
