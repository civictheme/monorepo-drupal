// phpcs:ignoreFile
import {
  boolean, radios, text,
} from '@storybook/addon-knobs';

import CivicThemeChip from './chip.twig';
import './chip';
import './chip.event';

export default {
  title: 'Atoms/Chip',
  parameters: {
    layout: 'centered',
  },
};

export const Chip = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const kind = radios(
    'Kind', {
      Default: 'default',
      Input: 'input',
    },
    'default',
    generalKnobTab,
  );

  const generalKnobs = {
    theme: radios(
      'Theme', {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      generalKnobTab,
    ),
    kind: () => kind,
    content: text('Chip label', 'Chip label', generalKnobTab),
    is_dismissible: (kind === 'default') ? boolean('Dismissible', false, generalKnobTab) : null,
    is_multiple: (kind === 'input') ? boolean('Is multiple', false, generalKnobTab) : null,
    is_selected: (kind === 'input') ? boolean('Is selected', false, generalKnobTab) : null,
    attributes: text('Additional attributes', '', generalKnobTab),
    modifier_class: text('Additional classes', '', generalKnobTab),
  };

  return CivicThemeChip({
    ...generalKnobs,
  });
};
