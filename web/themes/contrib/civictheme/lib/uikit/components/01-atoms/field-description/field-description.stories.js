// phpcs:ignoreFile
import CivicThemeFieldDescription from './field-description.twig';
import { knobBoolean, knobRadios, knobText, randomLink, randomSentence, shouldRender } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Atoms/Form Controls/Field Description',
  parameters: {
    layout: 'centered',
    storyLayoutSize: 'medium',
    knobs: {
      escapeHTML: false,
    },
  },
};

export const FieldDescription = (parentKnobs = {}) => {
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
    size: knobRadios(
      'Size',
      {
        Large: 'large',
        Regular: 'regular',
      },
      'regular',
      parentKnobs.size,
      parentKnobs.knobTab,
    ),
    content: knobText('Content', `Field message content sample. ${randomSentence(50)} ${randomLink()}`, parentKnobs.content, parentKnobs.knobTab),
    allow_html: knobBoolean('Allow HTML in content', true, parentKnobs.allow_html, parentKnobs.knobTab),
    modifier_class: knobText('Additional classes', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  return shouldRender(parentKnobs) ? CivicThemeFieldDescription(knobs) : knobs;
};
