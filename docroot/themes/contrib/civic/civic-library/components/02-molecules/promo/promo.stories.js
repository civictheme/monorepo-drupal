import {
  text, radios, object,
} from '@storybook/addon-knobs';
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
    call_to_action: object('Quick links', { title: 'Sign up', url: 'https://salsadigital.com.au/', new_window: true }, generalKnobTab),
  };

  return CivicPromo({
    ...generalKnobs,
  });
};
