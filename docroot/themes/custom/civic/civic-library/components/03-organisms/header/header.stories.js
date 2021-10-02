import { boolean, radios } from '@storybook/addon-knobs';
import { getSlots } from '../../00-base/base.stories';
import CivicHeader from './header.stories.twig';
import imageFile from '../../../assets/logo.png';
import './header.scss';


export default {
  title: 'Organisms/Header',
};

export const Header = () => {
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
    logo: boolean('With image', true, generalKnobTab) ? {
      src: imageFile,
      alt: 'Image alt text',
    } : false,
  };

  return CivicHeader({
    ...generalKnobs,
    ...getSlots([
      'top',
      'logo',
      'content',
      'bottom',
    ]),
  });
};
