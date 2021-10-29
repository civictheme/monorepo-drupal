import { radios } from '@storybook/addon-knobs';
import CivivBackToTop from './back-to-top.twig';
import './back-to-top';

export default {
  title: 'Molecules',
  parameters: {
    layout: 'centered',
  },
};

export const BackToTop = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

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

  return CivivBackToTop({
    ...generalKnobs,
  });
};
