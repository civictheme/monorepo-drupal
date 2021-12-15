import {
  boolean, radios, select, text,
} from '@storybook/addon-knobs';
import { getSlots } from '../../00-base/base.stories';
import CivicFooter from './footer.stories.twig';
import '../../00-base/responsive/responsive';
import '../../00-base/collapsible/collapsible';
import { generateMenuLinks } from '../../00-base/menu/menu.utils';

export default {
  title: 'Organisms/Footer',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Footer = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const theme = radios(
    'Theme',
    {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
    generalKnobTab,
  );

  const generalKnobs = {
    theme,
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  generalKnobs.logos = boolean('Show Logo', true, generalKnobTab) ? {
    mobile: {
      src: LOGOS.mobile[generalKnobs.theme],
      alt: 'Logo mobile alt text',
    },
    desktop: {
      src: LOGOS.desktop[generalKnobs.theme],
      alt: 'Logo desktop alt text',
    },
  } : null;

  generalKnobs.show_social_links = boolean('Show social links', true, generalKnobTab);
  generalKnobs.show_middle_links = boolean('Show middle links', true, generalKnobTab);
  generalKnobs.show_acknowledgement = boolean('Show acknowledgement', true, generalKnobTab);
  generalKnobs.show_copyright = boolean('Show copyright', true, generalKnobTab);

  if (generalKnobs.show_middle_links) {
    generalKnobs.links1 = generateMenuLinks(4, 1, false);
    generalKnobs.links2 = generateMenuLinks(4, 1, false);
    generalKnobs.links3 = generateMenuLinks(4, 1, false);
    generalKnobs.links4 = generateMenuLinks(4, 1, false);
  }

  if (boolean('Show background image', false, generalKnobTab)) {
    generalKnobs.background_image = BACKGROUNDS[theme][select('Background', Object.keys(BACKGROUNDS[theme]), Object.keys(BACKGROUNDS[theme])[0], generalKnobTab)];
  }

  return CivicFooter({
    ...generalKnobs,
    ...getSlots([
      'content_top1',
      'content_top2',
      'content_middle1',
      'content_middle2',
      'content_middle3',
      'content_middle4',
      'content_bottom1',
      'content_bottom2',
    ]),
  });
};
