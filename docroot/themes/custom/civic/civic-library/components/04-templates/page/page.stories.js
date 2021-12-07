// @todo This is an unfinished component. Stories still need to be implemented!
import { radios, text } from '@storybook/addon-knobs';
import CivicPage from './page.twig';

export default {
  title: 'Templates/Page',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Page = (knobTab) => {
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
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicPage({
    ...generalKnobs,
  });
};
