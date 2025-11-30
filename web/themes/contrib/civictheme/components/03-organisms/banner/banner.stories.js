import Component from './banner.twig';
import BannerData from './banner.stories.data';
import Constants from '../../../dist/constants.json'; // eslint-disable-line import/no-unresolved

const meta = {
  title: 'Organisms/Banner',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    content_top1: {
      control: { type: 'text' },
    },
    breadcrumb: {
      control: { type: 'array' },
    },
    content_top2: {
      control: { type: 'text' },
    },
    content_top3: {
      control: { type: 'text' },
    },
    content_middle: {
      control: { type: 'text' },
    },
    content: {
      control: { type: 'text' },
    },
    content_bottom: {
      control: { type: 'text' },
    },
    content_below: {
      control: { type: 'text' },
    },
    site_section: {
      control: { type: 'text' },
    },
    title: {
      control: { type: 'text' },
    },
    is_decorative: {
      control: { type: 'boolean' },
    },
    featured_image: {
      control: { type: 'object' },
    },
    background_image: {
      control: { type: 'object' },
    },
    background_image_blend_mode: {
      control: { type: 'select' },
      options: Constants.SCSS_VARIABLES['ct-background-blend-modes'],
    },
    modifier_class: {
      control: { type: 'text' },
    },
    attributes: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Banner = {
  parameters: {
    layout: 'fullscreen',
  },
  args: BannerData.args('light'),
};

export const BannerDark = {
  parameters: {
    layout: 'fullscreen',
    backgrounds: {
      default: 'Dark',
    },
  },
  args: BannerData.args('dark'),
};
