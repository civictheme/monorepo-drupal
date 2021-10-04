import { boolean, radios, text } from '@storybook/addon-knobs';

import CivicLogo from './logo.twig';
import './logo.scss';
import imageFile from "../../../assets/logo.png";

export default {
  title: 'Atom/Logo',
};

export const Logo = () => {
  const generalKnobTab = 'General';

  const generalKnobs = {
    theme: radios('Theme', {
      Light: 'light',
      Dark: 'dark',
    }, 'light', 'Theme', generalKnobTab),
    logo: boolean('With logo', true, generalKnobTab) ? {
      src: text('Logo', imageFile, generalKnobTab),
      alt: text('Logo alt', 'Image alt text', generalKnobTab),
    } : true,
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  return CivicLogo({
    ...generalKnobs,
  });
}
