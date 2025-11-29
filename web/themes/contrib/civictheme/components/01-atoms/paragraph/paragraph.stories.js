// phpcs:ignoreFile
import CivicThemeParagraph from './paragraph.twig';
import { knobBoolean, knobRadios, knobText, randomSentence, shouldRender } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Atoms/Paragraph',
  parameters: {
    layout: 'centered',
    storyLayoutSize: 'medium',
    knobs: {
      escapeHTML: false,
    },
  },
};

export const Paragraph = (parentKnobs = {}) => {
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
    content: knobText('Content', randomSentence(50, 'paragraph-content'), parentKnobs.content, parentKnobs.knobTab),
    size: knobRadios(
      'Size',
      {
        'Extra Large': 'extra-large',
        Large: 'large',
        Regular: 'regular',
        Small: 'small',
      },
      'regular',
      parentKnobs.size,
      parentKnobs.knobTab,
    ),
    allow_html: knobBoolean('Allow HTML in text', false, parentKnobs.allow_html, parentKnobs.knobTab),
    modifier_class: knobText('Additional classes', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  return shouldRender(parentKnobs) ? CivicThemeParagraph(knobs) : knobs;
};
