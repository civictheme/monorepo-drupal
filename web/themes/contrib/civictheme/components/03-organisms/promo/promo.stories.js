// phpcs:ignoreFile
import CivicThemePromo from './promo.twig';
import { knobBoolean, knobRadios, knobText, randomSentence, shouldRender, slotKnobs } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Organisms/Promo',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Promo = (parentKnobs = {}) => {
  const knobs = {
    theme: knobRadios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'dark',
      parentKnobs.theme,
      parentKnobs.knobTab,
    ),
    title: knobText('Title', 'Sign up for industry news and updates from CivicTheme', parentKnobs.title, parentKnobs.knobTab),
    content: knobText('Content', randomSentence(), parentKnobs.content, parentKnobs.knobTab),
    is_contained: knobBoolean('Contained', true, parentKnobs.is_contained, parentKnobs.knobTab),
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
    with_background: knobBoolean('With background', false, parentKnobs.with_background, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
    modifier_class: knobText('Additional classes', '', parentKnobs.modifier_class, parentKnobs.knobTab),
  };

  return shouldRender(parentKnobs) ? CivicThemePromo({
    ...knobs,
    ...slotKnobs([
      'content_top',
      'content_bottom',
    ]),
  }) : knobs;
};
