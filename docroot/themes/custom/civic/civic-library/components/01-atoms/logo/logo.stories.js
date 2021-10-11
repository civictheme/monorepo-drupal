import { boolean, radios, text } from '@storybook/addon-knobs';

import CivicLogo from './logo.twig';
import './logo.scss';
import imageFile from '../../../assets/logo.png';

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
    logo: boolean('With logo', true, generalKnobTab) ? {
      src: imageFile,
      alt: 'Logo alt text',
    } : true,
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  return CivicLogo({
    ...generalKnobs,
  });
};
