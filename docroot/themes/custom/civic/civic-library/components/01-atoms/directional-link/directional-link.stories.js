import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicDirectionalLink from './directional-link.twig';

export default {
  title: 'Atom/Directional Link',
  parameters: {
    layout: 'centered',
  },
};

export const DirectionalLink = () => CivicDirectionalLink({
  theme: radios(
    'Theme', {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
  ),
  direction: radios(
    'Direction', {
      Top: 'top',
      Bottom: 'bottom',
      Left: 'left',
      Right: 'right',
    },
    'left',
  ),
  text: text('Text', 'Top'),
  url: text('URL', '#top'),
  title: text('Text', 'Top link title'),
  disabled: boolean('Disabled', false),
  modifier_class: text('Additional class', ''),
});
