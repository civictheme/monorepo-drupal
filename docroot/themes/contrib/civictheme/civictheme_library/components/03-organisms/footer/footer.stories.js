// phpcs:ignoreFile
import {
  boolean, radios, select, text,
} from '@storybook/addon-knobs';
import { getSlots } from '../../00-base/base.utils';
import CivicThemeFooter from './footer.stories.twig';
import '../../00-base/responsive/responsive';
import '../../00-base/collapsible/collapsible';
import { generateMenuLinks } from '../../00-base/menu/menu.utils';
import { Logo } from '../../02-molecules/logo/logo.stories';

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

  generalKnobs.logo = boolean('Show logo', true, generalKnobTab) ? Logo('Logo', false) : null;

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
    generalKnobs.background_image = BACKGROUNDS[select('Background', Object.keys(BACKGROUNDS), Object.keys(BACKGROUNDS)[0], generalKnobTab)];
  }

  return CivicThemeFooter({
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
