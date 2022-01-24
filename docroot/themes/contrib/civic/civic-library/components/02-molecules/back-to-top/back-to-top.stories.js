import { radios } from '@storybook/addon-knobs';
import CivicBackToTop from './back-to-top.twig';

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

  const html = CivicBackToTop({
    ...generalKnobs,
  });

  return `<a id="top"></a><div class="example-container"><div class="example-container__page-content"></div>${html}</div>`;
};
