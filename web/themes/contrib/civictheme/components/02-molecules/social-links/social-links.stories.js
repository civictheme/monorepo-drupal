import Component from './social-links.twig';
import Icon from '../../00-base/icon/icon.twig';

const meta = {
  title: 'Molecules/Social Links',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    items: {
      control: { type: 'array' },
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
    items: [
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
        icon_html: Icon({
          symbol: 'linkedin',
          size: 'small',
        }),
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
      {
        icon_html: `<img class="ct-button__icon" width="16" height="16" src="./demo/images/demo1.jpg"/>`,
        url: 'https://www.dropbox.com',
        // Deliberately left without a title.
      },
    ],
    with_border: false,
    modifier_class: '',
    attributes: '',
  },
};
