import {
  boolean,
  radios,
  text,
} from '@storybook/addon-knobs';
import { getSlots } from '../../00-base/base.stories';
import CivicFooter from './footer.stories.twig';
import imageFile from '../../../assets/logo.png';

export default {
  title: 'Organisms/Footer',
};

const acknowledgement = 'We acknowledge the traditional owners of the country throughout Australia and their continuing connection to land, sea and community. We pay our respect to them and their cultures and to the elders past and present.';
const copyright = '© Commonwealth of Australia';
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
    image: boolean('With image', true, generalKnobTab) ? {
      src: imageFile,
      alt: 'Image alt text',
    } : false,
    showMiddleMenus: boolean('Show middle menus', true, generalKnobTab),
    acknowledgement: text('Acknowledgement', acknowledgement, generalKnobTab),
    copyright: text('Copyright text', copyright, generalKnobTab),
    socialLinks: boolean('Show social links', true, generalKnobTab),
  };

  return CivicFooter({
    ...generalKnobs,
    ...getSlots([
      'logo',
      'top_left',
      'top_right',
      'middle_left',
      'middle_right',
      'bottom_left',
      'bottom_right',
    ]),
  });
};
