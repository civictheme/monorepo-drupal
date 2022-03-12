import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicPromo from './promo.twig';

export default {
  title: 'Molecules/Promo',
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
      new_window: boolean('Link opens in new window', true, generalKnobTab),
      is_external: boolean('Link is external', true, generalKnobTab),
    },
  };

  return CivicPromo({
    ...generalKnobs,
  });
};
