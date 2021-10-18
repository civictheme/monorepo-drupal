import { boolean, radios } from '@storybook/addon-knobs';
import { getSlots } from '../../00-base/base.stories';
import CivicFooter from './footer.stories.twig';
import logoDesktopLight from '../../../assets/logo-desktop-light.png';
import logoDesktopDark from '../../../assets/logo-desktop-dark.png';
import logoMobileLight from '../../../assets/logo-mobile-light.png';
import logoMobileDark from '../../../assets/logo-mobile-dark.png';
import './footer.scss';

export default {
  title: 'Organisms/Footer',
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
  };

  generalKnobs.logos = boolean('Show Logo', true, generalKnobTab) ? {
    mobile: {
      src: generalKnobs.theme === 'light' ? logoMobileDark : logoMobileLight,
      alt: 'Logo mobile alt text',
    },
    desktop: {
      src: generalKnobs.theme === 'light' ? logoDesktopDark : logoDesktopLight,
      alt: 'Logo desktop alt text',
    },
  } : null;

  generalKnobs.show_social_links = boolean('Show social links', true, generalKnobTab);

  generalKnobs.show_middle_menus = boolean('Show middle menus', true, generalKnobTab);
  generalKnobs.show_acknowledgement = boolean('Show acknowledgement', true, generalKnobTab) ? 'We acknowledge the traditional owners of the country throughout Australia and their continuing connection to land, sea and community. We pay our respect to them and their cultures and to the elders past and present.' : null;
  generalKnobs.show_copyright = boolean('Show copyright', true, generalKnobTab) ? '© Commonwealth of Australia' : null;

  return CivicFooter({
    ...generalKnobs,
    ...getSlots([
      'content_top1',
      'content_top2',
      'content_top3',
      'content_middle1',
      'content_middle2',
      'content_middle3',
      'content_middle4',
      'content_bottom1',
      'content_bottom2',
    ]),
  });
};
