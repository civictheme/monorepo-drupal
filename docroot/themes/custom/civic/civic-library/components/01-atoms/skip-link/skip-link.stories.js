import { radios, text } from '@storybook/addon-knobs';

import CivicSkipLink from './skip-link.twig';

export default {
  title: 'Atoms/Skip Link',
};

export const SkipLink = () => CivicSkipLink({
  theme: radios(
    'Theme', {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
  ),
  text: text('Text', 'Skip to main content'),
  url: text('URL', '#main-content'),
  modifier_class: text('Additional class', ''),
});
