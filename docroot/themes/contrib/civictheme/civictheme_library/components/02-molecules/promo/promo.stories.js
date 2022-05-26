import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicPromo from './promo.twig';

export default {
  title: 'Molecules/Promo',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Promo = () => {
  const generalKnobTab = 'General';

  const generalKnobs = {
    theme: radios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'dark',
      generalKnobTab,
    ),
    title: text('Title', 'Sign up for industry news and updates from CivicTheme', generalKnobTab),
    content: text('Content', '', generalKnobTab),
    link: {
      text: text('Link text', 'Sign up', generalKnobTab),
      url: text('Link URL', 'https://example.com', generalKnobTab),
      is_new_window: boolean('Link opens in new window', true, generalKnobTab),
      is_external: boolean('Link is external', true, generalKnobTab),
    },
    vertical_space: radios(
      'Vertical space',
      {
        None: 'none',
        Top: 'top',
        Bottom: 'bottom',
        Both: 'both',
      },
      'none',
      generalKnobTab,
    ),
  };

  return CivicPromo({
    ...generalKnobs,
  });
};
