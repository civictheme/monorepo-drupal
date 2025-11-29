// phpcs:ignoreFile
import CivicThemeNextSteps from './next-step.twig';
import { knobBoolean, knobRadios, knobText, shouldRender, slotKnobs } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Molecules/Next Steps',
};

export const NextSteps = (parentKnobs = {}) => {
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
    title: knobText('Title', 'Next steps title from knob', parentKnobs.title, parentKnobs.knobTab),
    content: knobText('Content', 'Short summary explaining why this link is relevant.', parentKnobs.content, parentKnobs.knobTab),
    link: {
      text: knobText('Link text', 'Sign up', parentKnobs.link_text, parentKnobs.knobTab),
      url: knobText('Link URL', 'https://example.com', parentKnobs.link_url, parentKnobs.knobTab),
      is_new_window: knobBoolean('Link opens in new window', true, parentKnobs.link_is_new_window, parentKnobs.knobTab),
      is_external: knobBoolean('Link is external', true, parentKnobs.link_is_external, parentKnobs.knobTab),
    },
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
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
  };

  return shouldRender(parentKnobs) ? CivicThemeNextSteps({
    ...knobs,
    ...slotKnobs([
      'content_top',
      'content_bottom',
    ]),
  }) : knobs;
};
