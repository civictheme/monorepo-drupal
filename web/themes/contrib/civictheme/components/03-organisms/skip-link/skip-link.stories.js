// phpcs:ignoreFile
import CivicThemeSkipLink from './skip-link.twig';
import { knobRadios, knobText, shouldRender } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Organisms/Skip Link',
  parameters: {
    layout: 'fullscreen',
    docs: 'Press TAB on the keyboard for the Skip Link to appear',
    docsSize: 'large',
    docsPlacement: 'after',
  },
};

export const SkipLink = (parentKnobs = {}) => {
  const knobs = {
    theme: knobRadios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      parentKnobs.theme,
      parentKnobs.knobTab,
    ),
    text: knobText('Text', 'Skip to main content', parentKnobs.text, parentKnobs.knobTab),
    url: knobText('URL', '#main-content', parentKnobs.url, parentKnobs.knobTab),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
  };

  return shouldRender(parentKnobs) ? CivicThemeSkipLink(knobs) : knobs;
};
