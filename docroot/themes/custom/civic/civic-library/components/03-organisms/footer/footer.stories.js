import { boolean, radios } from '@storybook/addon-knobs';
import { getSlots } from '../../00-base/base.stories';
import CivicFooter from './footer.stories.twig';
import imageFile from '../../../assets/logo.png';
import './footer.scss';

export default {
  title: 'Organisms/Footer',
};

export const Footer = () => {
  const generalKnobTab = 'General';

  const generalKnobs = {
    theme: radios(
      'Theme',
      {
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
  };

  return CivicFooter({
    ...generalKnobs,
    ...getSlots([
      'logo',
      'top_left',
      'top_right',
      'middle',
      'bottom_left',
      'bottom_right',
    ]),
  });
};
