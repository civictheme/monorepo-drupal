import { text, boolean, radios } from '@storybook/addon-knobs';
import CivicBanner from './banner.twig'
import Breadcrumb from '../breadcrumb/breadcrumb.twig';
import './banner.scss';
import imageFile from "../../../assets/surface-945444.png";

export default {
  title: 'Molecule/Banner',
};

export const Banner = () => {
  const generalKnobTab = 'General';

  return CivicBanner({
    theme: radios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      generalKnobTab,
    ),
    breadcrumb: boolean('Show breadcrumb', false, generalKnobTab) ? Breadcrumb : false,
    title: text('Title', 'Latest news and media title for civic theme goes over multiple line'),
    content: text('Title', 'Banner summary using body copy which can run across multiple lines. Recommend limiting this summary to two or three lines..'),
    section: text('Section', 'Page section'),
    background: boolean('With background', true, generalKnobTab) ? {
      src: imageFile,
      alt: 'Image alt text',
    } : false,
    modifier_class: text('Additional class', '', generalKnobTab),
  });
};
