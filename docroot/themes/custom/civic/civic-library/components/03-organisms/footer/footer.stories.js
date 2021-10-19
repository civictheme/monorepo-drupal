import { boolean, radios, text } from '@storybook/addon-knobs';
import { getSlots } from '../../00-base/base.stories';
import CivicFooter from './footer.stories.twig';
import logoDesktopLight from '../../../assets/logo-desktop-light.png';
import logoDesktopDark from '../../../assets/logo-desktop-dark.png';
import './footer.scss';

export default {
  title: 'Organisms/Footer',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Footer = () => {
  const generalKnobTab = 'General';

  const generalKnobs = {
    theme: radios(
      'Theme', {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      generalKnobTab,
    ),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  generalKnobs.logos = boolean('Show Logo', true, generalKnobTab) ? {
    mobile: {
      src: generalKnobs.theme === 'light' ? logoDesktopDark : logoDesktopLight,
      alt: 'Logo mobile alt text',
    },
    desktop: {
      src: generalKnobs.theme === 'light' ? logoDesktopDark : logoDesktopLight,
      alt: 'Logo desktop alt text',
    },
  } : null;

  generalKnobs.show_social_links = boolean('Show social links', true, generalKnobTab);
  generalKnobs.show_middle_links = boolean('Show middle links', true, generalKnobTab);
  generalKnobs.show_acknowledgement = boolean('Show acknowledgement', true, generalKnobTab);
  generalKnobs.show_copyright = boolean('Show copyright', true, generalKnobTab);

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
