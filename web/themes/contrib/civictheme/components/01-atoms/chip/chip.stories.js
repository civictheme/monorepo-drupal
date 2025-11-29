// phpcs:ignoreFile
import CivicThemeChip from './chip.twig';
import './chip';
import './chip.event.stories';
import { knobBoolean, knobRadios, knobText, shouldRender } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Atoms/Chip',
  parameters: {
    layout: 'centered',
  },
};

export const Chip = (parentKnobs = {}) => {
  const theme = knobRadios(
    'Theme',
    {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
    parentKnobs.theme,
    parentKnobs.knobTab,
  );

  const size = knobRadios(
    'Size',
    {
      Large: 'large',
      Regular: 'regular',
      Small: 'small',
      None: '',
    },
    'regular',
    parentKnobs.size,
    parentKnobs.knobTab,
  );

  const kind = knobRadios(
    'Kind',
    {
      Default: 'default',
      Input: 'input',
    },
    'default',
    parentKnobs.kind,
    parentKnobs.knobTab,
  );

  // Keep parentKnobs above to preserve order.
  const knobs = {
    theme,
    kind,
    size,
    is_multiple: (kind === 'input') ? knobBoolean('Is multiple', false, parentKnobs.is_multiple, parentKnobs.knobTab) : null,
    is_selected: (kind === 'input') ? knobBoolean('Is selected', false, parentKnobs.is_selected, parentKnobs.knobTab) : null,
    content: knobText('Chip label', 'Chip label', parentKnobs.content, parentKnobs.knobTab),
    modifier_class: knobText('Additional classes', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  return shouldRender(parentKnobs) ? CivicThemeChip(knobs) : knobs;
};
