// phpcs:ignoreFile
import CivicThemeContentLink from './content-link.twig';
import { knobBoolean, knobRadios, knobText, randomUrl, shouldRender } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Atoms/Content Link',
  parameters: {
    layout: 'centered',
  },
};

export const ContentLink = (parentKnobs = {}) => {
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
    text: knobText('Text', 'Link text', parentKnobs.text, parentKnobs.knobTab),
    title: knobText('Title', 'Link title', parentKnobs.title, parentKnobs.knobTab),
    url: knobText('URL', randomUrl(), parentKnobs.url, parentKnobs.knobTab),
    is_external: knobBoolean('Is external', false, parentKnobs.is_external, parentKnobs.knobTab),
    is_new_window: knobBoolean('Open in a new window', false, parentKnobs.is_new_window, parentKnobs.knobTab),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  return shouldRender(parentKnobs) ? CivicThemeContentLink(knobs) : knobs;
};
