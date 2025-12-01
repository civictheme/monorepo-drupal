import Component from './social-links.twig';
import { demoIcon } from '../../00-base/base.utils';
import Icon from '../../00-base/icon/icon.twig';

const generateItems = () => [
  {
    icon_html: `<img class="ct-button__icon" width=16 height=16 src="${demoIcon()}"/>`,
    url: 'https://www.dropbox.com',
    // Deliberately left without a title.
  },
  {
    title: 'Facebook',
    icon: 'facebook',
    url: 'https://www.facebook.com',
  },
  {
    title: 'Instagram',
    icon: 'instagram',
    url: 'https://www.instagram.com',
  },
  {
    title: 'Icon with inline SVG',
    // icon_html should take precedence.
    icon_html: Icon({ symbol: 'linkedin', size: 'small' }),
    icon: 'linkedin',
    url: 'https://www.linkedin.com',
  },
  {
    title: 'X',
    icon: 'x',
    url: 'https://www.twitter.com',
  },
  {
    title: 'YouTube',
    icon: 'youtube',
    url: 'https://www.youtube.com',
  },
];

const meta = {
  title: 'Organisms/Social Links',
  component: Component,
  render: (args) => Component({
    ...args,
    items: args.with_items ? generateItems() : null,
  }),
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    with_items: {
      control: { type: 'boolean' },
    },
    with_border: {
      control: { type: 'boolean' },
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

export const SocialLinks = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    with_items: true,
    with_border: true,
    modifier_class: '',
    attributes: '',
  },
};
