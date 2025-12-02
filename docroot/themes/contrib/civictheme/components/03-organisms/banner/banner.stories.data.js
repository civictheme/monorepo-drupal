import BreadcrumbComponent from '../../02-molecules/breadcrumb/breadcrumb.twig';
import Constants from '../../../dist/constants.json'; // eslint-disable-line import/no-unresolved
import { demoImage } from '../../00-base/base.utils';

const breadcrumbLinks = [
  { text: 'Home', url: '/' },
  { text: 'Parent page', url: '/parent' },
  { text: 'Current page', url: '/parent/current' },
];

export default {
  args: (theme = 'dark') => ({
    theme,
    title: 'Providing visually engaging digital experiences',
    background_image: Constants.BACKGROUNDS[Object.keys(Constants.BACKGROUNDS)[0]],
    background_image_blend_mode: Constants.SCSS_VARIABLES['ct-background-blend-modes'][0],
    featured_image: {
      url: demoImage(0),
      alt: 'Featured image alt text',
    },
    is_decorative: true,
    site_section: 'Site section name',
    breadcrumb: BreadcrumbComponent({
      theme,
      links: breadcrumbLinks,
      active_is_link: false,
    }),
    show_content_text: true,
    show_content_below: false,
    modifier_class: '',
  }),
};
