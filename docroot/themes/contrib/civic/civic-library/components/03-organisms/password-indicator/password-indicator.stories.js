import { radios } from '@storybook/addon-knobs';

import CivicPasswordIndicatorStory from './password-indicator.stories.twig';

export default {
  title: 'Organisms/Form/Password Indicator',
};

export const PasswordIndicator = (knobTab) => {
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

  return CivicPasswordIndicatorStory({
    ...generalKnobs,
  });
};
