import { radios, text } from '@storybook/addon-knobs';
import CivicAlert from './alert.twig';

export default {
  title: 'Organisms/Alert',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Alert = (knobTab) => {
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
    type: radios(
      'Type',
      {
        Status: 'status',
        Error: 'error',
        Warning: 'warning',
        Success: 'success',
      },
      'status',
      generalKnobTab,
    ),
    title: text('Title', 'Site information', generalKnobTab),
    description: text('Summary', 'Filium morte multavit si sine causa, nollem me tamen laudandis. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vivamus vel elit laoreet, dignissim arcu sit amet, vulputate risus.', generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  return CivicAlert({
    ...generalKnobs,
  });
};
