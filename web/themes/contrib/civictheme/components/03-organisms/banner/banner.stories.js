import Component from './banner.stories.twig';
import BreadcrumbComponent from '../../02-molecules/breadcrumb/breadcrumb.twig';
import Constants from '../../../dist/constants.json'; // eslint-disable-line import/no-unresolved
import { demoImage, getSlots } from '../../00-base/base.utils';

const breadcrumbLinks = [
  { text: 'Home', url: '/' },
  { text: 'Parent page', url: '/parent' },
  { text: 'Current page', url: '/parent/current' },
];

const meta = {
  title: 'Organisms/Banner',
  component: Component,
  render: (args) => {
    const breadcrumb = args.with_breadcrumb ? BreadcrumbComponent({
      theme: args.theme,
      links: breadcrumbLinks,
      active_is_link: false,
    }) : '';

    return Component({
      theme: args.theme,
      title: args.title,
      background_image: args.with_background_image ? Constants.BACKGROUNDS[args.background] : null,
      background_image_blend_mode: args.with_background_image ? args.blend_mode : null,
      featured_image: args.with_featured_image ? {
        url: demoImage(0),
        alt: 'Featured image alt text',
      } : null,
      is_decorative: args.is_decorative,
      site_section: args.with_site_section ? 'Site section name' : '',
      breadcrumb,
      show_content_text: args.show_content_text,
      show_content_below: args.show_content_below,
      modifier_class: args.modifier_class,
      ...getSlots([
        'content_top1',
        'content_top2',
        'content_top3',
        'content_middle',
        'content',
        'content_bottom',
      ], args.show_slots),
    });
  },
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    title: {
      control: { type: 'text' },
    },
    with_background_image: {
      control: { type: 'boolean' },
    },
    background: {
      control: { type: 'select' },
      options: Object.keys(Constants.BACKGROUNDS),
      if: { arg: 'with_background_image' },
    },
    blend_mode: {
      control: { type: 'select' },
      options: Constants.SCSS_VARIABLES['ct-background-blend-modes'],
      if: { arg: 'with_background_image' },
    },
    with_featured_image: {
      control: { type: 'boolean' },
    },
    is_decorative: {
      control: { type: 'boolean' },
    },
    with_site_section: {
      control: { type: 'boolean' },
    },
    with_breadcrumb: {
      control: { type: 'boolean' },
    },
    show_content_text: {
      control: { type: 'boolean' },
    },
    show_content_below: {
      control: { type: 'boolean' },
    },
    show_slots: {
      control: { type: 'boolean' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Banner = {
  parameters: {
    layout: 'fullscreen',
  },
  args: {
    theme: 'dark',
    title: 'Providing visually engaging digital experiences',
    with_background_image: true,
    background: Object.keys(Constants.BACKGROUNDS)[0],
    blend_mode: Constants.SCSS_VARIABLES['ct-background-blend-modes'][0],
    with_featured_image: true,
    is_decorative: true,
    with_site_section: true,
    with_breadcrumb: true,
    show_content_text: true,
    show_content_below: false,
    show_slots: false,
    modifier_class: '',
  },
};
