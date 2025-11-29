// phpcs:ignoreFile
import CivicThemeWebform from './webform.twig';
import { knobBoolean, knobRadios, knobText, shouldRender } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Organisms/Webform',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Webform = (parentKnobs = {}) => {
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
    referenced_webform: knobText('Title', 'Webform title', parentKnobs.webform_title, parentKnobs.knobTab),
    with_background: knobBoolean('With background', false, parentKnobs.with_background, parentKnobs.knobTab),
    vertical_spacing: knobRadios(
      'Vertical spacing',
      {
        None: 'none',
        Top: 'top',
        Bottom: 'bottom',
        Both: 'both',
      },
      'none',
      parentKnobs.vertical_spacing,
      parentKnobs.knobTab,
    ),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  return shouldRender(parentKnobs) ? CivicThemeWebform(knobs) : knobs;
};
