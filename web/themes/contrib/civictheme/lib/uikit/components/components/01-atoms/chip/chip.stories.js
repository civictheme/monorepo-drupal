// phpcs:ignoreFile
import {
  boolean, radios, text,
} from '@storybook/addon-knobs';

import CivicThemeChip from './chip.twig';
import './chip';
import './chip.event.stories';

export default {
  title: 'Atoms/Chip',
  parameters: {
    layout: 'centered',
  },
};

export const Chip = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const theme = radios(
    'Theme',
    {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
    generalKnobTab,
  );

  const size = radios(
    'Size',
    {
      Large: 'large',
      Regular: 'regular',
      Small: 'small',
      None: '',
    },
    'regular',
    generalKnobTab,
  );

  const kind = radios(
    'Kind',
    {
      Default: 'default',
      Input: 'input',
    },
    'default',
    generalKnobTab,
  );

  const generalKnobs = {
    theme,
    kind,
    size,
    is_multiple: (kind === 'input') ? boolean('Is multiple', false, generalKnobTab) : null,
    is_selected: (kind === 'input') ? boolean('Is selected', false, generalKnobTab) : null,
    content: text('Chip label', 'Chip label', generalKnobTab),
    modifier_class: text('Additional classes', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeChip({
    ...generalKnobs,
  });
};
