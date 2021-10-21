import { boolean, radios, text } from '@storybook/addon-knobs';

import CivicLogo from './logo.twig';
import './logo.scss';
import desktopLogoFile from '../../../assets/logo.png';
import mobileLogoFile from '../../../assets/logo-mobile.png';

export default {
  title: 'Atom/Logo',
};

export const Logo = () => {
  const generalKnobTab = 'General';

  const generalKnobs = {
    theme: radios('Theme', {
      Light: 'light',
      Dark: 'dark',
    }, 'light', generalKnobTab),
    logos: boolean('With logo', true, generalKnobTab) ? {
      mobile: {
        src: mobileLogoFile,
        alt: 'Logo mobile alt text',
      },
      desktop: {
        src: desktopLogoFile,
        alt: 'Logo desktop alt text',
      },
    } : true,
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  const html = CivicLogo({
    ...generalKnobs,
  });

  return `<div class="civic-logo-example">${html}</div>`;
};
