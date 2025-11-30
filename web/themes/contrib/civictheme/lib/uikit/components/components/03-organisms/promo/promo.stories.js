// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicThemePromo from './promo.twig';
import { getSlots, randomSentence } from '../../00-base/base.utils';

export default {
  title: 'Organisms/Promo',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Promo = () => {
  const generalKnobTab = 'General';

  const generalKnobs = {
    theme: radios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'dark',
      generalKnobTab,
    ),
    title: text('Title', 'Sign up for industry news and updates from CivicTheme', generalKnobTab),
    content: text('Content', randomSentence(), generalKnobTab),
    is_contained: boolean('Contained', true, generalKnobTab),
    link: {
      text: text('Link text', 'Sign up', generalKnobTab),
      url: text('Link URL', 'https://example.com', generalKnobTab),
      is_new_window: boolean('Link opens in new window', true, generalKnobTab),
      is_external: boolean('Link is external', true, generalKnobTab),
    },
    vertical_spacing: radios(
      'Vertical spacing',
      {
        None: 'none',
        Top: 'top',
        Bottom: 'bottom',
        Both: 'both',
      },
      'none',
      generalKnobTab,
    ),
    with_background: boolean('With background', false, generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
    modifier_class: text('Additional classes', '', generalKnobTab),
  };

  return CivicThemePromo({
    ...generalKnobs,
    ...getSlots([
      'content_top',
      'content_bottom',
    ]),
  });
};
